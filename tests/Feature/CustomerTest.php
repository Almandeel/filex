<?php

namespace Tests\Feature;

use App\User;
use App\Customer;

class CustomerTest extends BaseTestCase
{
    /** @test */
    public function user_can_create_customer()
    {
        $response = $this->actingAs($this->user)->post(route('customers.store'), [
            'name' => 'test',
            'phone' => '0147852369'
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals(Customer::first()->name, 'test');
        $this->assertEquals(Customer::first()->phone, '0147852369');
    }

    /** @test */
    public function user_can_update_customer()
    {
        $this->actingAs($this->user)->post(route('customers.store'), [
            'name' => 'test',
            'phone' => '0147852369'
        ])->assertSessionHas('success');

        $response = $this->actingAs($this->user)->patch(route('customers.update', 1), [
            'name' => 'test2test',
            'phone' => '0123456789'
        ])->assertSessionHas('success');

        $this->assertEquals(Customer::first()->name, 'test2test');
        $this->assertEquals(Customer::first()->phone, '0123456789');
    }

    /** @test */
    public function user_can_delete_customer()
    {
        $this->actingAs($this->user)->post(route('customers.store'), [
            'name' => 'test',
            'phone' => '0147852369'
        ])->assertSessionHas('success');

        $response = $this->actingAs($this->user)->delete(route('customers.destroy', 1));

        $response->assertSessionHas('success');

        $this->assertEquals(Customer::all()->count(), 0);
    }
}
