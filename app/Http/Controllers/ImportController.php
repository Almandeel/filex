<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\CustomerImport;
use App\Imports\SupplierImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function customers(Request $request) {
        Excel::import(new CustomerImport, $request->file('customers_excel'));

        session()->flash('success', 'تمت العملية بنجاح');

        return back(); 
    }

    public function suppliers(Request $request) {
        Excel::import(new SupplierImport, $request->file('suppliers_excel'));
        session()->flash('success', 'تمت العملية بنجاح');
        return back(); 
    }


}
