<?php

use Illuminate\Database\Seeder;

use App\User;
use App\Employee;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emp = Employee::create([
            'name' => 'superaadmin', 
            'phone' => '0987654321', 
            'address' => 'superadmin', 
            'salary' => 200000
        ]);

        $superadmin = User::create([
            'username' => 'super',
            'password' => bcrypt('123456'),
            'employee_id' => $emp->id,
        ]);

        $superadmin->attachRole('superadmin');
    }
}
