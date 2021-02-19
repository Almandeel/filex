<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use App\Exports\SupplierExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    
    public function customers() {
        return Excel::download(new CustomerExport, 'العملاء.xlsx');
    }

    public function suppliers() {
        return Excel::download(new SupplierExport, 'الموردين.xlsx');
    }

}
