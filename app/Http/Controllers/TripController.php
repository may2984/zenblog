<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\TripStore as ModelStore;
use App\Models\Trip as Model;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class TripController extends Controller
{
    private $label, $folder;

    public function __construct()
    {
        $this->label = 'Trip';
        $this->folder = 'trip';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $trips = Model::select('id', 'name', 'status')->get()->toArray();
        $members = Member::select('id', 'name', 'status')->get()->toArray();

        $data = [
            'trips' => $trips,
            'members' => $members
        ];

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.' . $this->folder . '.create', [
            'label' => $this->label,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModelStore $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $created = Model::create([
                'name' => $request->input('name'),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

        if (!$created) {
            return response()->json(['error', 'message' => 'Error! Try again']);
        } else {
            // if a trip is created add its members
            $created->members()->attach($request->input('members'));
            return response()->json([
                'success',
                'message' => $this->label . ' added',
                'data' => [$created->fresh()]
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Model::select('id', 'name', 'status')->find($id);

        return view('admin.' . $this->folder . '.edit', [
            'data' => $data,
            'label' => $this->label,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = Model::find($id);

        $updated = $model->update([
            'name' => $request->get('name'),
        ]);

        if ($updated) {
            return redirect(route($this->folder . '.create'))->with('success', $this->label . ' edited');
        } else {
            return redirect()->back()->with('error', 'Error! try again');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = Model::where('id', $id)->delete();

        if ($deleted) {
            return response()->json([
                'success', $this->label . ' deleted'
            ]);
        }

        return response()->json([
            'error', 'Error! Try again'
        ]);
    }

    public function toggleStatus($status, $id)
    {
        $updated = Model::where('id', $id)->update(['status' => $status]);

        if (!$updated) {
            return response()->json(['type' => 'error']);
        }

        return response()->json(['type' => 'success']);
    }

    public function tripList()
    {
        $data = Model::with('members:name,created_at')->select('id', 'name', 'status')->get();

        return view('admin.' . $this->folder . '.list', [
            'data' => $data,
            'label' => $this->label,
        ]);
    }

    public function massDelete(Request $request)
    {
        $id =  Arr::join($request->input('trip_list_checkbox'), ",");

        $deleted = 1; //Model::destroy($id);

        if ($deleted) {
            return response()->json([
                'success', $this->label . ' Deleted',
            ]);
        } else {
            return response()->json([
                'error', 'Error! Try again'
            ]);
        }
    }
}
