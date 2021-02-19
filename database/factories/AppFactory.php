<?php

use App\{Item, Unit, Customer, Supplier, Employee, Account};
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(Unit::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'phone' => 01234560147,
        'account_id' => Account::newCustomer()->id
    ];
});

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'phone' => 01234560147,
        'account_id' => Account::newSupplier()->id
    ];
});

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'phone' => 01234560147,
        'address' => $faker->sentence,
    ];
});