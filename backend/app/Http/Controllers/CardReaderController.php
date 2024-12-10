<?php

namespace App\Http\Controllers;

use App\Http\Requests\CardReaderRequest;
use App\Models\CardReader;
use Illuminate\Http\Request;

class CardReaderController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->except('index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $resource = CardReader::with('accessGate')->when($request->keyword, function ($q) use ($request) {
            $q->where('name', 'LIKE', "%{$request->keyword}%");
        })->orderBy($request->sortColumn ?: 'name', $request->sortOrder ?: 'asc');

        return $request->paginated == 'true' ? $resource->paginate($request->per_page) : $resource->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CardReaderRequest $request)
    {
        CardReader::create($request->all());
        return ['message' => 'Data telah disimpan'];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardReaderRequest $request, CardReader $cardReader)
    {
        $cardReader->update($request->all());
        return ['message' => 'Data telah diupdate'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CardReader $cardReader)
    {
        $cardReader->delete();
        return ['message' => 'Data telah dihapus'];
    }
}
