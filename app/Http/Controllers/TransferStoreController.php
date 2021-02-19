<?php

namespace App\Http\Controllers;

use App\Store;
use App\ItemStore;
use App\ItemStoreUnit;
use App\TransferStore;
use App\TransferStoreItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransferStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = TransferStore::all();
        return view('dashboard.transferstore.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $stores = Store::all();
        if($request->from_store) {
            $items_store = ItemStore::where('store_id', request()->from_store)->get();
            return view('dashboard.transferstore.create', compact('stores', 'items_store'));
        }
        return view('dashboard.transferstore.create', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transfer_store = TransferStore::create([
            'from_store'    => $request->from_store,
            'to_store'      => $request->to_store,
            'user_id'       => auth()->user()->id,
        ]);

        for ($i=0; $i < count($request->quantity) ; $i++) { 
            // from store
            $from_items_store = ItemStoreUnit::find($request->item_store_unit_id[$i]);
            $from_items_store->update([
                'quantity' => $from_items_store->quantity - $request->quantity[$i]
            ]);


            $items_store = ItemStore::where('item_id', $from_items_store->itemStore->item_id)->where('store_id', $request->to_store)->first();
            
            $item_store_unit = $items_store ? $items_store->items->where('item_unit_id', $from_items_store->item_unit_id)->first() : null;

            if($item_store_unit != null && $request->quantity[$i] > 0) {
                    $item_store_unit->update([
                        'quantity' => $item_store_unit->quantity + $request->quantity[$i]
                    ]);
            }else {
                if($request->quantity[$i] > 0) {

                    $items_store = ItemStore::create([
                        'item_id'   => $from_items_store->itemUnit->item_id,
                        'store_id'  => $request->to_store,
                    ]);

                    $items_store_unit = ItemStoreUnit::create([
                        'item_store_id'         => $items_store->id,
                        'quantity'              => $request->quantity[$i],
                        'price_purchase'        => $from_items_store->price_purchase,
                        'price_sell'            => $from_items_store->price_sell,
                        'item_unit_id'          => $from_items_store->item_unit_id,
                    ]);

                }
            }

            $transfer_store_items = TransferStoreItem::create([
                'transfer_store_id' => $transfer_store->id,
                'item_store_unit_id' => $from_items_store->id,
                'quantity' => $request->quantity[$i],
            ]);
        }

        return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TransferStore  $transferStore
     * @return \Illuminate\Http\Response
     */
    public function show($transferStore)
    {
        $transferStore = TransferStore::find($transferStore);
        return view('dashboard.transferstore.show', compact('transferStore'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TransferStore  $transferStore
     * @return \Illuminate\Http\Response
     */
    public function edit( $transferStore)
    {
        $stores = Store::all();
        $transferStore = TransferStore::find($transferStore);
        return view('dashboard.transferstore.edit', compact('transferStore', 'stores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TransferStore  $transferStore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransferStore $transferStore)
    {

        // for ($i=0; $i < count($request->quantity) ; $i++) { 
        //     // from store
        //     $from_items_store = ItemStoreUnit::find($request->item_store_unit_id[$i]);
        //     $from_items_store->update([
        //         'quantity' => $from_items_store->quantity - $request->quantity[$i]
        //     ]);


        //     $items_store = ItemStore::where('item_id', $from_items_store->itemStore->item_id)->where('store_id', $request->to_store)->first();
            
        //     $item_store_unit = $items_store ? $items_store->items->where('item_unit_id', $from_items_store->item_unit_id)->first() : null;

        //     if($item_store_unit != null && $request->quantity[$i] > 0) {
        //             $item_store_unit->update([
        //                 'quantity' => $item_store_unit->quantity + $request->quantity[$i]
        //             ]);
        //     }else {
        //         if($request->quantity[$i] > 0) {

        //             $items_store = ItemStore::create([
        //                 'item_id'   => $from_items_store->itemUnit->item_id,
        //                 'store_id'  => $request->to_store,
        //             ]);

        //             $items_store_unit = ItemStoreUnit::create([
        //                 'item_store_id'         => $items_store->id,
        //                 'quantity'              => $request->quantity[$i],
        //                 'price_purchase'        => $from_items_store->price_purchase,
        //                 'price_sell'            => $from_items_store->price_sell,
        //                 'item_unit_id'          => $from_items_store->item_unit_id,
        //             ]);

        //         }
        //     }

        //     $transfer_store_items = TransferStoreItem::create([
        //         'transfer_store_id' => $transfer_store->id,
        //         'item_store_unit_id' => $from_items_store->id,
        //         'quantity' => $request->quantity[$i],
        //     ]);
        // }

        // return back()->with('success', 'تمت العملية بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TransferStore  $transferStore
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransferStore $transferStore)
    {
        //
    }

    /**
     * get items 
     */
    // public function getItems($store_id) {
    //     $items_store = ItemStore::where('store_id', $store_id)->get()->map(function ($items) {
    //         return [
    //             'name' => $item
    //         ];
    //     });

    //     return response()->json($items_store);
    // }
}
