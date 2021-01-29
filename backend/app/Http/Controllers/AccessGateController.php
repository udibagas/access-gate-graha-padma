<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessGateRequest;
use App\Models\AccessGate;
use Illuminate\Http\Request;

class AccessGateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = AccessGate::when($request->keyword, function ($q) use ($request) {
            $q->where('name', 'LIKE', "%{$request->keyword}%");
        })->orderBy($request->sortColumn ?: 'name', $request->sortOrder ?: 'asc');

        return $request->paginated == 'true' ? $resource->paginate($request->per_page) : $resource->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccessGateRequest $request)
    {
        AccessGate::create($request->all());
        return ['message' => 'Data telah disimpan'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccessGate  $accessGate
     * @return \Illuminate\Http\Response
     */
    public function show(AccessGate $accessGate)
    {
        return $accessGate;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccessGate  $accessGate
     * @return \Illuminate\Http\Response
     */
    public function update(AccessGateRequest $request, AccessGate $accessGate)
    {
        $accessGate->update($request->all());
        return ['message' => 'Data telah diupdate'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccessGate  $accessGate
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccessGate $accessGate)
    {
        $accessGate->delete();
        return ['message' => 'Data telah dihapus'];
    }
}
