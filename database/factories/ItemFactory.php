<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Item, Unit};
use Faker\Generator as Faker;

use Modules\Restaurant\Models\{Menu};
$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->afterCreating(Item::class, static function (Item $item) {
    $menus = Menu::all();
    $item->menus()->attach(array_filter(array_rand($menus->modelKeys(), 3)));
    $units = Unit::all();
    $units_keys = array_filter(array_rand($units->modelKeys(), 3));
    foreach ($units_keys as $key) {
        $price = random_int(80, 450) . '.' . random_int(0, 9) . random_int(0, 9);
        $item->units()->attach($key, ['price' => $price]);
    }
});