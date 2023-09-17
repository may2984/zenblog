<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use App\Models\TripExpenses;
use Carbon\Carbon;

class TripExpensesController extends Controller
{
    private $label, $folder;

    public function __construct()
    {
        $this->label = 'Trip Expenses';
        $this->folder = 'trip.expenses';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $trip_id = $request->input("trip_id");
        $member_id = $request->input("member_id");
        $date_time = $request->input("date_time") ? Carbon::createFromFormat('Y-m-d', $request->input("date_time")) : Carbon::now();
        $amount = $request->input("amount");
        $item = $request->input("item");

        $data = [
            'trip_id' => $trip_id,
            'member_id' => $member_id,
            'date_time' => $date_time,
            'amount' => $amount,
            'item' => $item
        ];

        $created = (new TripExpenses)::create($data);

        if ($created) {
            return back()->with('success', 'Expenses Added');
        } else {
            return back()->with('Error', 'Try! again');
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Returns list of expenses based on expense id
     */

    public function list()
    {
        $expenses = (new TripExpenses)::with('member:id,name')->whereTripId(103)->orderByDesc('date_time')->get();
        // dd($expenses);

        return view('admin.' . $this->folder . '.list', [
            'label' => $this->label,
            'expenses' => $expenses
        ]);
    }
}
