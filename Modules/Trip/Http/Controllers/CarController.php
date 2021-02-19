<?php

namespace Modules\Trip\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Trip\Models\Car;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $cars = Car::paginate();
        return view('trip::cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('trip::cars.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $request->validate([
            'vin_number' => 'required | string',
            'car_number' => 'required | string',
            'empty_weight' => 'required | string',
            'max_weight' => 'required | string',
        ]);

        $car = Car::create($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('trip::cars.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('trip::cars.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        $request->validate([
            'vin_number' => 'required | string',
            'car_number' => 'required | string',
            'empty_weight' => 'required | string',
            'max_weight' => 'required | string',
        ]);

        $car->update($request->all());

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $car = Car::find($id);

        $car->delete();

        return back()->with('success', 'تمت العملية بنجاح');
    }
}
