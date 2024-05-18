<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessLogRequest;
use App\Jobs\TakeSnapshot;
use App\Models\AccessGate;
use App\Models\AccessLog;
use App\Models\Member;
use App\Models\Snapshot;
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
                        ->orWhere('card_number', 'LIKE', "%{$request->keyword}%")
                        ->orWhere('plate_number', 'LIKE', "%{$request->keyword}%");
                });
            })->orWhere('card_number', 'LIKE', "%{$request->keyword}%")
                ->orWhere('plate_number', 'LIKE', "%{$request->keyword}%");
        })->when($request->access_gate_id, function ($q) use ($request) {
            $q->whereIn('access_gate_id', $request->access_gate_id);
        })->when($request->type, function ($q) use ($request) {
            $q->whereHas('accessGate', function ($q) use ($request) {
                $q->whereIn('type', $request->type);
            });
        })->when($request->dateRange, function ($q) use ($request) {
            $q->whereRaw('DATE(created_at) BETWEEN ? AND ? ', $request->dateRange);
        })->orderBy($request->sortColumn ?: 'created_at', $request->sortOrder ?: 'desc');

        $data = $request->paginated == 'true' ? $resource->paginate($request->per_page) : $resource->get();

        if ($request->action == 'export') {
            return [
                'filename' => 'AccessLog_' . date('Y_m_d_H_i_s') . '.xls',
                'data' => $data->map(function ($item) {
                    return [
                        'Waktu' => $item->created_at->format('Y-m-d H:i:s'),
                        'Gate' => $item->accessGate->name,
                        'Jenis' => $item->accessGate->type,
                        'Nama' => $item->member->name,
                        'Nomor Kartu' => $item->card_number,
                        'Plat Nomor' => $item->plate_number,
                    ];
                })
            ];
        }

        return $data;
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

        if (!$member->active) {
            return response('INACTIVE', 403);
        }

        if ($member->is_expired) {
            return response('EXPIRED', 403);
        }

        $gate   = AccessGate::where('host', $request->ip)->first();

        if ($member->group == Member::GROUP_MEMBER) {
            $lastAccess = $member->accessLogs()->latest()->first();

            if ($gate->type == 'IN') {
                if ($lastAccess && $lastAccess->accessGate->type == 'IN') {
                    return response('BELUM OUT', 403);
                }
            }

            if ($gate->type == 'OUT') {
                if (!$lastAccess || $lastAccess->accessGate->type == 'OUT') {
                    return response('BELUM IN', 403);
                }
            }
        }

        $accessLog = AccessLog::create([
            'member_id' => $member->id,
            'access_gate_id' => $gate->id,
            'card_number' => $request->card_number,
            'plate_number' => $member->plate_number
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
        Snapshot::truncate();
        Storage::deleteDirectory('snapshots');
        return ['message' => 'Data telah dihapus'];
    }
}
