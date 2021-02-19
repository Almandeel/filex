<?php

namespace Modules\Trip\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Trip\Models\Driver;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $drivers = Driver::paginate();
        return view('trip::drivers.index', compact('drivers'));
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
            'name' => 'required | string',
            'phone' => 'required | string',
            'address' => 'required | string',
        ]);

        $driver = Driver::create($request->all());

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
        $driver = Driver::find($id);
        $request->validate([
            'name' => 'required | string',
            'phone' => 'required | string',
            'address' => 'required | string',
        ]);

        $driver->update($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $driver = Driver::find($id);
        $driver->delete();
        return back()->with('success', 'تمت العملية بنجاح');
    }
}
