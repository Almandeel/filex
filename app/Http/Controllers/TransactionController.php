<?php

namespace App\Http\Controllers;

use App\Transaction;
use App\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct() {
        $this->middleware('permission:transactions-create')->only(['create', 'store']);
        $this->middleware('permission:transactions-read')->only(['index', 'show']);
        $this->middleware('permission:transactions-update')->only(['edit', 'update']);
        $this->middleware('permission:transactions-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }
    
    /**
    * Transaction a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
            'amount'      => 'required | numeric',
            'safe_id'      => 'required | numeric',
            ]);
            $date = $request->year . '-';
            $date .= $request->month < 10 ? '0' . $request->month : $request->month;
            $request['month'] = $date;
            if(!array_key_exists($request->type, Transaction::TYPES)) $request['type'] = Transaction::TYPE_DEBT;
            $transaction = Transaction::create($request->all());
            
            return back()->with('success', 'تمت المعاملة بنجاح');
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function show(Transaction $transaction)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function edit(Transaction $transaction)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Transaction $transaction)
    {
        if(\Hash::check($request->password, auth()->user()->password)){
            $request->validate([
            'amount'      => 'required | numeric',
            ]);
            $date = $request->year . '-';
            $date .= $request->month < 10 ? '0' . $request->month : $request->month;
            $request['month'] = $date;
            if(!array_key_exists($request->type, Transaction::TYPES)) $request['type'] = Transaction::TYPE_DEBT;
            $transaction->update($request->all());
            if(isset($transaction->entry)) $transaction->entry->delete();
            
            if($request->paymethod != 0){
                $sb = Account::find($request->paymethod);
                $entry = $sb->withDrawn($request->amount, Account::debts()->id, $request->details);
                $transaction->update(['entry_id' => $entry->id]);
            }
            
            session()->flash('success', 'تم تعديل المعاملة بنجاح');
            
            return back();
        }
        return back()->with('error', 'كلمة المرور خاطئة');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Transaction  $transaction
    * @return \Illuminate\Http\Response
    */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return back()->with('success', 'تم حذف المعاملة بنجاح');
    }
}