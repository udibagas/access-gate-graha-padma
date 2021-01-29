<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessLogRequest;
use App\Jobs\TakeSnapshot;
use App\Models\AccessLog;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = AccessLog::when($request->keyword, function ($q) use ($request) {
            $q->whereHas('member', function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->keyword}%");
            });
        })->when($request->access_gate_id, function ($q) use ($request) {
            $q->whereIn('access_gate_id', $request->access_gate_id);
        })->orderBy($request->sortColumn ?: 'created_at', $request->sortOrder ?: 'desc');

        return $request->paginated == 'true' ? $resource->paginate($request->per_page) : $resource->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccessLogRequest $request)
    {
        $accessLog = AccessLog::create($request->all());
        TakeSnapshot::dispatch($accessLog);
        return ['message' => 'Data telah disimpan'];
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
    public function destroy(AccessLog $accessLog)
    {
        $accessLog->delete();
        return ['message' => 'Data telah dihapus'];
    }
}
