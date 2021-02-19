<?php

namespace Modules\Trip\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Trip\Models\Car;
use Modules\Trip\Models\Trip;
use Modules\Trip\Models\State;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $trips = Trip::paginate();
        $states = State::all();
        $cars = Car::whereStatus(0)->get();
        return view('trip::index', compact('trips', 'states', 'cars'));
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
            'from' => 'required | string',
            'to' => 'required | string',
            'car_id' => 'required | string',
            'amount' => 'required | string',
        ]);

        $trip = Trip::create($request->all());

        if($trip) {
            $trip->car->update([
                'status' => 1
            ]);
        }

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
        $trip = Trip::find($id);

        if($request->type) {
            if($trip) {
                $trip->update([
                    'status' => 1
                ]);

                $trip->car->update([
                    'status' => 0
                ]);
            }
        }else {
            $request->validate([
                'from' => 'required | string',
                'to' => 'required | string',
                'car_id' => 'required | string',
                'amount' => 'required | string',
            ]);
    
            $trip->update($request->all());
        }

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
