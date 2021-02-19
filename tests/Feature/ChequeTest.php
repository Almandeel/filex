<?php

namespace Tests\Feature;

use App\Cheque;
use App\Entry;

class ChequeTest extends BaseTestCase
{
    public function test_user_can_inserte_cheque()
    {
        $this->actingAs($this->user)->post(route('cheques.store'), [
            'type' => rand(1, 2),
            'number' => 9012415024505402,
            'amount' => 54027,
            'due_date' => now()->addMonth(),
            'from' => 1,
            'user_id' => $this->user->id,
        ])->assertRedirect(route('cheques.index'));

        $this->assertEquals(1, Cheque::all()->count());
    }

    public function test_payment_entry_register_after_successfully_create_cheque()
    {
        $this->createDummyCheque();
        $this->assertEquals(1, Entry::all()->count());
    }

    public function test_change_cheque_status_to_delivered()
    {
        $this->createDummyCheque();

        $this->actingAs($this->user)->put(route('cheques.update', 1), [
            'status' => 2,
        ])->assertSessionHas('success');

        $this->assertEquals(2, Cheque::first()->status);
    }

    public function createDummyCheque()
    {
        $this->actingAs($this->user)->post(route('cheques.store'), [
            'type' => rand(1, 2),
            'number' => 9012415024505402,
            'amount' => 54027,
            'due_date' => now()->addMonth(),
            'from' => 1,
            'user_id' => $this->user->id,
            'status' => rand(0, 2),
        ])->assertRedirect(route('cheques.index'));

        $this->assertEquals(1, Cheque::all()->count());
    }
}
