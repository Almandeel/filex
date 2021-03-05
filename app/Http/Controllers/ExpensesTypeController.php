<?php

namespace App\Http\Controllers;

use App\ExpensesType;
use Illuminate\Http\Request;

class ExpensesTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:expenses-type-create')->only(['create', 'store']);
        $this->middleware('permission:expenses-type-read')->only(['index', 'show']);
        $this->middleware('permission:expenses-type-update')->only(['edit', 'update']);
        $this->middleware('permission:expenses-type-delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = ExpensesType::all();
        return view('dashboard.expensestype.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.expensestype.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | string | max:100'
        ]);

        ExpensesType::create($request->all());

        if($request->next == 'back'){
            return redirect()->route('expensestypes.create')->with('success', 'تمت العملية بنجاح');
        }else{
            return redirect()->route('expensestypes.index')->with('success', 'تمت العملية بنجاح');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExpensesType  $expensesType
     * @return \Illuminate\Http\Response
     */
    public function show(ExpensesType $expensesType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ExpensesType  $expensesType
     * @return \Illuminate\Http\Response
     */
    public function edit($expense)
    {
        $expense = ExpensesType::find($expense);
        return view('dashboard.expensestype.edit', compact('expense'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpensesType  $expensesType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expensesType = ExpensesType::find($id);
        $request->validate([
            'name' => 'required | string | max:100'
        ]);

        $expensesType->update($request->all());

        if($request->next == 'back'){
            return redirect()->route('expensestypes.create')->with('success', 'تمت العملية بنجاح');
        }else{
            return redirect()->route('expensestypes.index')->with('success', 'تمت العملية بنجاح');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpensesType  $expensesType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpensesType $expensesType)
    {
        //
    }
}
