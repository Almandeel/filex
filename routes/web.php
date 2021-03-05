<?php

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', 'DashboardController')->name('dashboard.index');
    Route::get('/home', function(){
        return redirect()->route('dashboard.index');
    })->name('dashboard.home');

    Route::get('/loader', function(){
        return view('components.loader');
    });
});


Route::group(['middleware' => 'auth', 'prefix' => 'management'], function() {
    Route::get('/management', 'DashboardController')->name('management.index');
    Route::post('/reset', 'DashboardController@reset')->name('dashboard.reset');
    Route::resource('users', 'UserController');
    Route::put('profile', 'UserController@profile')->name('users.profile');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('employees', 'EmployeeController');
    Route::resource('items', 'ItemController');
    Route::resource('stores', 'StoreController');
    Route::resource('expenses', 'ExpenseController');
    Route::get('stores/users/add', 'StoreController@addUser')->name('stores.users.add');
    Route::post('stores/users/remove', 'StoreController@removeUser')->name('stores.users.remove');
    Route::get('stores/items/add', 'StoreController@addItem')->name('stores.items.add');
    Route::post('stores/items/remove', 'StoreController@removeItem')->name('stores.items.remove');
    Route::get('stores/suppliers/add', 'StoreController@addSupplier')->name('stores.suppliers.add');
    Route::post('stores/suppliers/remove', 'StoreController@removeSupplier')->name('stores.suppliers.remove');
    Route::get('stores/customers/add', 'StoreController@addCustomer')->name('stores.customers.add');
    Route::post('stores/customers/remove', 'StoreController@removeCustomer')->name('stores.customers.remove');
    Route::resource('categories', 'CategoryController');
    Route::get('category/{id}', 'CategoryController@subCategory');
    
    Route::post('items/stores/attach/{item}', 'ItemController@attachStore')->name('items.stores.attach');
    Route::post('items/stores/detach/{item}', 'ItemController@detachStore')->name('items.stores.detach');
    Route::post('items/units/attach/{item}', 'ItemController@attachUnit')->name('items.units.attach');
    Route::post('items/units/detach/{item}', 'ItemController@detachUnit')->name('items.units.detach');
    Route::post('items/units/update/{item}', 'ItemController@updateUnit')->name('items.units.update');
    
    Route::resource('customers', 'CustomerController');
    Route::resource('collaborators', 'CollaboratorController');
    Route::resource('suppliers', 'SupplierController');
    Route::get('supplier/bill/{id}', 'SupplierController@showBill')->name('supplier.bill');
    Route::resource('units', 'UnitController');
    Route::resource('transferstores', 'TransferStoreController');
    
    
    Route::resource('transactions', 'TransactionController', [
    'except' => ['edit', 'create', 'show']
    ]);
    
    
    Route::group([
    'prefix'    => 'employees',
    // 'as'        => 'branches.',
    // 'namespace' => 'branch',
    ], function() {
        Route::group([
        'prefix' => '{employee}',
        // 'middleware' => [['CheckYear']],
        ], function () {
            Route::resource('salaries', 'SalaryController',[
            'only' => ['index', 'create', 'store', 'destroy']
            ]);
        });
    });
    Route::resource('bills', 'BillController');
    Route::resource('safes', 'SafeController');
    Route::resource('transfers', 'TransferController');
    Route::resource('invoices', 'InvoiceController');
    Route::resource('payments', 'PaymentController');
    Route::resource('entries', 'EntryController');
    Route::resource('charges', 'ChargeController');
    Route::resource('accounts', 'AccountController');
    Route::get('report/items', 'ItemController@reportItems')->name('items_report');
    Route::get('reports/invoice', 'ReportController@invoice')->name('reports.invoice');
    Route::get('reports/safes', 'ReportController@safes')->name('reports.safes');
    Route::get('reports/safe', 'ReportController@safe')->name('reports.safe');
    Route::get('reports/purchases', 'ReportController@purchases')->name('reports.purchases');
    Route::get('reports/sells', 'ReportController@sells')->name('reports.sells');
    Route::get('reports/profits', 'ReportController@profits')->name('reports.profits');
    Route::get('reports/stores', 'ReportController@stores')->name('reports.stores');
    Route::get('reports/statement', 'ReportController@statement')->name('reports.statement');
    Route::get('reports/quantities', 'ReportController@quantities')->name('reports.quantities');
    
    Route::get('customers/{customer}/statement', 'CustomerController@statement')->name('customers.statement');
    Route::get('suppliers/{supplier}/statement', 'SupplierController@statement')->name('suppliers.statement');
    
    Route::get('cheques/{cheque}/update-status/{value}', 'ChequeController@updateStatus')->name('cheques.update.status');
    Route::resource('cheques', 'ChequeController');
    Route::resource('expensestypes', 'ExpensesTypeController');

    
    Route::get('notifications', function() {
        return auth()->user()->notifications;
    });
    
    
    
    // report route
    Route::get('report/items', 'ItemController@reportItems')->name('items_report');
    Route::get('report/salary/{id}' , 'ReportController@salary')->name('report.salary');
    Route::get('report/salaries/{id}' , 'ReportController@salaries')->name('report.salaries');
    Route::get('reports/invoice', 'ReportController@invoice');
    Route::get('report/bill/{id}' , 'ReportController@bill')->name('bill.print');
    Route::get('report/billreceipt/{id}' , 'ReportController@billReceipt')->name('bill.receipt');
    Route::get('report/invoicereceipt/{id}' , 'ReportController@invoiceReceipt')->name('invoice.receipt');
    Route::get('report/invoice/{id}' , 'ReportController@invoice')->name('invoice.print');
    
    
    //Export Route
    Route::get('export/customers', 'ExportController@customers')->name('export.customers');
    Route::get('export/suppliers', 'ExportController@suppliers')->name('export.suppliers');
    
    //Imports Route
    Route::post('imports/customers', 'ImportController@customers')->name('imports.customers');
    Route::post('imports/suppliers', 'ImportController@suppliers')->name('imports.suppliers');
    
    
    //example excel route
    Route::get('example/excel', 'DashboardController@example')->name('excel.example');


    Route::resource('cashiers', 'CashiersController');
    
});


Auth::routes();