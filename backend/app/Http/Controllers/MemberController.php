<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberRequest;
use App\Models\AccessLog;
use App\Models\Member;
use App\Models\Snapshot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            $q->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('card_number', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('plate_number', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('address', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('phone', 'LIKE', "%{$request->keyword}%")
                    ->orWhere('id_number', 'LIKE', "%{$request->keyword}%");
            });
        })->when($request->expired, function ($q) use ($request) {
            if ($request->expired[0] == 'yes') {
                $q->whereRaw('DATE(NOW()) > expired_date');
            }

            if ($request->expired[0] == 'no') {
                $q->whereRaw('DATE(NOW()) <= expired_date OR expired_date IS NULL');
            }
        })->when($request->group, function ($q) use ($request) {
            $q->where('group', (int) $request->group[0]);
        })->when($request->active, function ($q) use ($request) {
            $q->where('active', (int) $request->active[0]);
        })->when($request->sex, function ($q) use ($request) {
            $q->where('sex', (int) $request->sex[0]);
        })->orderBy($request->sortColumn ?: 'name', $request->sortOrder ?: 'asc');

        $data = $request->paginated == 'true' ? $resource->paginate($request->per_page) : $resource->get();

        if ($request->action == 'export') {
            return [
                'filename' => 'Data-Member-' . date('Y-m-d-H-i-s') . '.xls',
                'data' => $data->map(function ($item, $index) {
                    return [
                        'No' => ++$index,
                        'Nama' => $item->name,
                        'Jenis Kelamin' => $item->sex_label,
                        'No Identitas' => $item->id_number,
                        'Alamat' => $item->address,
                        'No HP' => $item->phone,
                        'Group' => $item->group_name,
                        'Nomor Kartu' => $item->card_number,
                        'Plat Nomor' => $item->plate_number,
                        'Masa Berlaku' => $item->expired_date,
                        'Status' => $item->status
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

        if ($member->accessLogs) {
            Storage::delete($member->accessLogs->map(function ($i) {
                return $i->path;
            }));

            $member->accessLogs()->delete();
        }

        return ['message' => 'Data telah dihapus'];
    }

    public function deleteAll()
    {
        Member::truncate();
        AccessLog::truncate();
        Snapshot::truncate();
        Storage::deleteDirectory('snapshots');

        return ['message' => 'Data telah dihapus'];
    }

    public function import(Request $request)
    {
        $request->validate(['rows' => 'required']);

        $response = DB::transaction(function () use ($request) {
            foreach ($request->rows as $row) {
                $data = [
                    'name' => $row['name'],
                    'sex' => $row['sex'],
                    'id_number' => $row['id_number'],
                    'address' => $row['address'],
                    'phone' => $row['phone'],
                    'card_number' => $row['card_number'],
                    'plate_number' => $row['plate_number'],
                    'expired_date' => Member::parseExcelDate($row['expired_date']),
                    'active' => $row['active'],
                    'group' => $row['group']
                ];

                Member::updateOrCreate(['card_number' => $row['card_number']], $data);
            }

            return response(['message' => 'Data telah diimport'], 201);
        });

        return $response;
    }
}
