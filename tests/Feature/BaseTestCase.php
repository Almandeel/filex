<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class BaseTestCase extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * New instance from user.
     *
     * @var \App\User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');

        $this->user = User::first();
    }

    protected function dummyTestData()
    {
        return [
            //
        ];
    }
}
