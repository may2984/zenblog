<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MemberStore;
use App\Models\Member as Model;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    private $label, $folder, $store;

    public function __construct()
    {
        $this->label = 'Member';
        $this->folder = 'member';
        $this->store = route('member.store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::select('id','name','status')->get();
        return response()->json( $data );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.'.$this->folder.'.create',[
            'label' => $this->label,
            'store' => $this->store,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $data = ['name' => $request->input('name')];  
      
        $created = Model::create( $data );

        if( !$created )
        {
            return back()->with('error', 'Error! Try again');
        }
        else
        {
            return back()->with('success' , $this->label.' added');        
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
        $data = Model::select('id','name','status')->find($id);

        return view('admin.'.$this->folder.'.edit',[
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

        if( $updated )
        {
            return redirect(route($this->folder.'.create'))->with('success', $this->label.' edited');
        }
        else
        {  
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

        if($deleted){
            return response()->json([
                'success', $this->label.' deleted'
            ]);
        }

        return response()->json([
            'error', 'Error! Try again'
        ]);
    }

    public function toggleStatus($status, $id)
    {   
        $updated = Model::where('id', $id)->update(['status' => $status]);
        
        if( !$updated ){
            return response()->json(['type' => 'error']);
        }

        return response()->json(['type' => 'success']);        
    }
}
