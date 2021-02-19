<?php

namespace Tests\Feature;

class BillsTest extends BaseTestCase
{

    public function test_index_page()
    {
        $this->actingAs($this->user)->get(route('bills.index'))
            ->assertViewIs('dashboard.bills.index')
            ->assertViewHas('bills');
    }

    public function test_bill_validation()
    {
        $this->actingAs($this->user)->from(route('bills.index'))->post(route('bills.store'), [])
            ->assertStatus(500);
    }
}
