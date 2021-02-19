<?php

use App\{Item, Unit, Customer, Supplier, Employee, Store};
use Illuminate\Database\Seeder;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $store = Store::create(['name' => 'Store']);
        
        factory(Item::class, 10)->create();
        factory(Unit::class, 5)->create();

        foreach(Item::all() as $item){
        	$item->units()->sync(rand(1, 5));
            $store->items()->attach($item->id);
        }

        factory(Supplier::class)->create();
        factory(Customer::class)->create();
        factory(Employee::class)->create();

    }
}
