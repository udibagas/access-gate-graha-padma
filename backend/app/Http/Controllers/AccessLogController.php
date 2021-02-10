<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessLogRequest;
use App\Jobs\TakeSnapshot;
use App\Models\AccessGate;
use App\Models\AccessLog;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccessLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('registeredDevice')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = AccessLog::when($request->keyword, function ($q) use ($request) {
            $q->whereHas('member', function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->keyword}%")
                        ->orWhere('card_number', 'LIKE', "%{$request->keyword}%");
                });
            });
        })->when($request->access_gate_id, function ($q) use ($request) {
            $q->whereIn('access_gate_id', $request->access_gate_id);
        })->when($request->type, function ($q) use ($request) {
            $q->whereHas('accessGate', function ($q) use ($request) {
                $q->whereIn('type', $request->type);
            });
        })->when($request->dateRange, function ($q) use ($request) {
            $q->whereRaw('DATE(created_at) BETWEEN ? AND ? ', $request->dateRange);
        })->orderBy($request->sortColumn ?: 'created_at', $request->sortOrder ?: 'desc');

        return $request->paginated == 'true' ? $resource->paginate($request->per_page) : $resource->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $member = Member::where('card_number', $request->card_number)->first();

        if (!$member) {
            return response('UNREGISTERED', 404);
        }

        if ($member->is_expired) {
            return response('EXPIRED', 403);
        }

        $gate   = AccessGate::where('host', $request->ip())->first();

        $accessLog = AccessLog::create([
            'member_id' => $member->id,
            'access_gate_id' => $gate->id
        ]);

        TakeSnapshot::dispatch($accessLog);
        return response('OK');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessLog  $accessLog
     * @return \Illuminate\Http\Response
     */
    public function show(AccessLog $accessLog)
    {
        return $accessLog->load(['snapshots']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccessLog  $accessLog
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        AccessLog::truncate();
        Storage::deleteDirectory('snapshots');
        return ['message' => 'Data telah dihapus'];
    }

    public function export(Request $request)
    {
        $data = AccessLog::when($request->keyword, function ($q) use ($request) {
            $q->whereHas('member', function ($q) use ($request) {
                $q->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->keyword}%")
                        ->orWhere('card_number', 'LIKE', "%{$request->keyword}%");
                });
            });
        })->when($request->access_gate_id, function ($q) use ($request) {
            $q->whereIn('access_gate_id', $request->access_gate_id);
        })->when($request->type, function ($q) use ($request) {
            $q->whereHas('accessGate', function ($q) use ($request) {
                $q->whereIn('type', $request->type);
            });
        })->when($request->dateRange, function ($q) use ($request) {
            $q->whereRaw('DATE(created_at) BETWEEN ? AND ? ', $request->dateRange);
        })->orderBy($request->sortColumn ?: 'created_at', $request->sortOrder ?: 'desc')
            ->get()->map(function ($item) {
                return [
                    'Waktu' => $item->created_at->format('Y-m-d H:i:s'),
                    'Gate' => $item->accessGate->name,
                    'Jenis' => $item->accessGate->type,
                    'Nama' => $item->member->name,
                    'Nomor Kartu' => $item->member->card_number,
                    'Plat Nomor' => $item->member->plate_number,
                ];
            });

        return [
            'filename' => 'AccessLog_' . date('Y_m_d_H_i_s') . 'xls',
            'data' => $data
        ];
    }
}
