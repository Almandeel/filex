<?php

namespace Modules\Trip\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Trip\Models\Car;
use Modules\Trip\Models\Trip;
use Modules\Trip\Models\State;
use Modules\Trip\Models\Driver;
use Modules\Trip\Models\Expense;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\Support\Renderable;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $from_date = Carbon::parse($request->from_date ?? now()->subDay(3)->startOfDay());
        $to_date = Carbon::parse($request->to_date ?? now())->endOfDay();

        $trips = Trip::whereStatus('0')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->when($request->state_id != 'all' && $request->state_id != null, function ($q) use($request) {return $q->where('from', $request->state_id);})
            ->when($request->car_id != 'all' && $request->car_id != null, function ($q) use($request) {return $q->where('car_id', $request->car_id);})
            ->when($request->driver_id != 'all' && $request->driver_id != null, function ($q) use($request) {return $q->where('driver_id', $request->driver_id);})
            ->orderBy('created_at', 'DESC')
            ->paginate();
        $states = State::all();
        $cars = Car::get();
        $drivers = Driver::get();
        return view('trip::index', compact('trips', 'states', 'cars', 'drivers'));
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
        $trip = Trip::find($id);
        $expenses = Expense::where('trip_id', $id)->get();
        return view('trip::show', compact('expenses', 'trip'));
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
        $trip = Trip::find($id);
        
        $trip->delete();
        
        return back()->with('success', 'تمت العملية بنجاح');

    }


    public function archive(Request $request)
    {
        $from_date = Carbon::parse($request->from_date ?? now()->subDay(3)->startOfDay());
        $to_date = Carbon::parse($request->to_date ?? now())->endOfDay();

        $trips = Trip::whereStatus('1')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->when($request->state_id != 'all' && $request->state_id != null, function ($q) use($request) {return $q->where('from', $request->state_id);})
            ->when($request->car_id != 'all' && $request->car_id != null, function ($q) use($request) {return $q->where('car_id', $request->car_id);})
            ->when($request->driver_id != 'all' && $request->driver_id != null, function ($q) use($request) {return $q->where('driver_id', $request->driver_id);})
            ->orderBy('created_at', 'DESC')
            ->paginate();
        $states = State::all();
        $cars = Car::get();
        $drivers = Driver::get();
        return view('trip::archive', compact('trips', 'states', 'cars', 'drivers'));
    }
}
