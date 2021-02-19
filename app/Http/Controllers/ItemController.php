<?php

namespace App\Http\Controllers;

use PDF;
use App\Item;
use App\Unit;
use App\Store;
use App\Category;
use Dompdf\Dompdf;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    
    public function __construct() {
        $this->middleware('permission:items-create')->only(['create', 'store']);
        $this->middleware('permission:items-read')->only(['index', 'show']);
        $this->middleware('permission:items-update')->only(['edit', 'update']);
        $this->middleware('permission:items-delete')->only('destroy');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $items = Item::all();
        $units = Unit::all();
        $stores = Store::all();
        return view('dashboard.items.index', compact('items', 'units', 'stores'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $stores = Store::all();
        $units = Unit::all();
        $categories = Category::whereNull('parent_id')->get();
        return view('dashboard.items.create', compact('stores', 'units', 'categories'));
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
            'name' => 'required | string | max:45',
            'image' => 'mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $data = $request->only(['name']);

        if ($request->has('image')) {
            $fileName = time().'.'.$request->image->extension();
            
            $request->image->move(public_path('images/items'), $fileName);
            $data['image'] = $fileName;
        }
        $item = Item::create($data);
        
        if ($request->has('stores_names')) {
            for ($i=0; $i < count($request->stores_names); $i++) {
                $store_name = $request->stores_names[$i];
                $store = Store::firstOrCreate(['name' => $store_name]);
                $item->stores()->attach($store->id);
                if(!in_array($store->id , auth()->user()->storesIds())) {
                    $store->users()->attach(auth()->user()->id);
                }
            }
        }
        
        if ($request->has(['units_names'])) {
            for ($i=0; $i < count($request->units_names); $i++) {
                $unit_name = $request->units_names[$i];
                // $unit_price = $request->units_prices[$i] ?? 0;
                $unit = Unit::firstOrCreate(['name' => $unit_name]);
                // , ['price' => $unit->itemUnit ? $unit->itemUnit->price : 0]
                $item->units()->attach($unit->id);
            }
        }
        
        return redirect()->route('items.index')->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Item  $item
    * @return \Illuminate\Http\Response
    */
    public function show(Item $item)
    {
        return view('dashboard.items.show', compact('item'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Item  $item
    * @return \Illuminate\Http\Response
    */
    public function edit(Item $item)
    {
        $stores = Store::all();
        $units = Unit::all();
        $categories = Category::whereNull('parent_id')->get();
        return view('dashboard.items.edit', compact('item', 'stores', 'units', 'categories'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Item  $item
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Item $item)
    {
        $request->validate([
        'name' => 'required | string | max:45',
        'image' => 'mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        
        $data = $request->only(['name']);

        if ($request->has('image')) {
            $fileName = time().'.'.$request->image->extension();
            
            $request->image->move(public_path('images/items'), $fileName);
            $data['image'] = $fileName;
        }
        
        $item->update($data);
        
        if ($request->has('stores_names')) {
            $item->stores()->detach();
            for ($i=0; $i < count($request->stores_names); $i++) {
                $store_name = $request->stores_names[$i];
                $store = Store::firstOrCreate(['name' => $store_name]);
                if(!in_array($store->id , auth()->user()->storesIds())) {
                    $store->users()->attach(auth()->user()->id);
                }
                $item->stores()->attach($store->id);
            }
        }
        
        if ($request->has(['units_names'])) {
            $item->units()->detach();
            for ($i=0; $i < count($request->units_names); $i++) {
                $unit_name = $request->units_names[$i];
                // $unit_price = $request->units_prices[$i];
                //['price' => $unit->itemUnit ? $unit->itemUnit->price : 0]
                    $unit = Unit::firstOrCreate(['name' => $unit_name]);
                    $item->units()->attach($unit->id);
            }
        }
        
        
        
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Item  $item
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Item $item)
    {
        
        $result = $item->delete();
        if ($result) {
            return redirect()->route('items.index')->with('success', 'تمت العملية بنجاح');
        }
        
        return back()->with('error', 'فشلت العملية');
        
    }
    
    public function attachStore(Request $request, Item $item){
        $request->validate(['store_id' => 'required|numeric|exists:stores,id']);
        $item->stores()->attach($request->store_id);
        return back()->with('success', 'تم إضافة المخزن إلى المنتج');
    }
    
    public function detachStore(Request $request, Item $item){
        $request->validate(['store_id' => 'required|numeric|exists:stores,id']);
        $item->stores()->detach($request->store_id);
        return back()->with('success', 'تم حذف المخزن من المنتج');
    }
    
    public function attachUnit(Request $request, Item $item){
        $request->validate(['unit_id' => 'required|numeric|exists:units,id']);
        $item->units()->attach($request->unit_id);
        return back()->with('success', 'تم إضافة الوحدة إلى المنتج');
    }
    
    public function detachUnit(Request $request, Item $item){
        $request->validate(['unit_id' => 'required|numeric|exists:units,id']);
        $item->units()->detach($request->unit_id);
        return back()->with('success', 'تم حذف الوحدة من المنتج');
    }
    
    public function updateUnit(Request $request, Item $item){
        $request->validate(['unit_id' => 'required|numeric|exists:units,id', 'price' => 'sometimes|numeric', 'status' => 'sometimes|numeric']);
        $data = $request->only(['price', 'status']);
        $item->units()->updateExistingPivot($request->unit_id, $data);
        return back()->with('success', 'تمت العملية بنجاح');
    }
    
    public function reportItems() {
        $items = Item::all();
        $view = PDF::loadView('dashboard.items.report', compact('items'));
        // $view->stream('قائمة المنتجات' . '.pdf',  array("Attachment" => false));
        
        // $html = view('dashboard.items.report', compact('items'))->render();
        // return @\PDF::loadHTML($html, 'utf-8')->stream();
        // $view->render();
        // $pdf = $view->stream("filename.pdf", array("Attachment" => false));
        
        // return $pdf;
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('dashboard.items.report', compact('items'))->render());
        
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser
        $dompdf->stream("filename.pdf", array("Attachment" => false));
    }
}