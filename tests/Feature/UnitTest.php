<?php

namespace Tests\Feature;

use App\User;
use App\Unit;

class UnitTest extends BaseTestCase
{
    /** @test */
    public function user_can_create_unit()
    {
        $response = $this->actingAs($this->user)->post(route('units.store'), [
            'name' => 'test',
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals(Unit::first()->name, 'test');
    }

    /** @test */
    public function user_can_update_unit()
    {
        $this->actingAs($this->user)->post(route('units.store'), [
            'name' => 'test',
        ])->assertSessionHas('success');

        $response = $this->actingAs($this->user)->patch(route('units.update', 1), [
            'name' => 'test2test',
        ])->assertSessionHas('success');

        $this->assertEquals(Unit::first()->name, 'test2test');
    }

    /** @test */
    public function user_can_delete_unit()
    {
        $this->actingAs($this->user)->post(route('units.store'), [
            'name' => 'test',
        ])->assertSessionHas('success');

        $response = $this->actingAs($this->user)->delete(route('units.destroy', 1));

        $response->assertSessionHas('success');

        $this->assertEquals(Unit::all()->count(), 0);
    }
}
