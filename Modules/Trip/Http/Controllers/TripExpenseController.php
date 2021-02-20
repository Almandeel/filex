<?php

namespace Modules\Trip\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Trip\Models\Trip;
use Modules\Trip\Models\Expense;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class TripExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $trips = Trip::whereStatus(0)->get();
        $expenses = Expense::paginate();
        return view('trip::expenses.index', compact('expenses', 'trips'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('trip::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => 'required | string',
            'amount' => 'required | string',
            'expense_type' => 'required | string',
        ]);

        $expense = Expense::create($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('trip::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('trip::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);

        $request->validate([
            'trip_id' => 'required | string',
            'amount' => 'required | string',
            'expense_type' => 'required | string',
        ]);

        $expense->update($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $expense = Expense::find($id);
        $expense->delete();
        return back()->with('success', 'تمت العملية بنجاح');
    }
}
