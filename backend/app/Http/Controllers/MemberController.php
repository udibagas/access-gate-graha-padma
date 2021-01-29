<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $resource = Member::when($request->keyword, function ($q) use ($request) {
            $q->where('name', 'LIKE', "%{$request->keyword}%");
        })->when($request->expired, function ($q) use ($request) {
            if ($request->expired[0] == 'yes') {
                $q->whereRaw('DATE(NOW()) > expired_date');
            }

            if ($request->expired[0] == 'no') {
                $q->whereRaw('DATE(NOW()) <= expired_date OR expired_date IS NULL');
            }
        })->orderBy($request->sortColumn ?: 'name', $request->sortOrder ?: 'asc');

        return $request->paginated == 'true' ? $resource->paginate($request->per_page) : $resource->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MemberRequest $request)
    {
        Member::create($request->all());
        return ['message' => 'Data telah disimpan'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function show(Member $member)
    {
        return $member;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function update(MemberRequest $request, Member $member)
    {
        $member->update($request->all());
        return ['message' => 'Data telah diupdate'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Member  $member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return ['message' => 'Data telah dihapus'];
    }

    public function import(Request $request)
    {
        // TODO
    }
}
