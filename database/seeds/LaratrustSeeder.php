<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        $this->truncateLaratrustTables();

        $config = config('laratrust_seeder.role_structure');
        $userPermission = config('laratrust_seeder.permission_structure');
        $mapPermission = collect(config('laratrust_seeder.permissions_map'));

        foreach ($config as $key => $modules) {

            // Create a new role
            $role = \App\Role::firstOrCreate([
                'name' => $key,
                'display_name' => ucwords(str_replace('_', ' ', $key)),
                'description' => ucwords(str_replace('_', ' ', $key))
            ]);
            $permissions = [];

            $this->command->info('Creating Role '. strtoupper($key));

            // Reading role permission modules
            foreach ($modules as $module => $value) {

                foreach (explode(',', $value) as $p => $perm) {

                    $permissionValue = $mapPermission->get($perm);

                    $permissions[] = \App\Permission::firstOrCreate([
                        'name' => $module . '-' . $permissionValue,
                        'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                    ])->id;

                    $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                }
            }

            // Attach all permissions to the role
            $role->permissions()->sync($permissions);

            $this->command->info("Creating '{$key}' user");

            // Create default user for each role
            // $user = \App\User::create([
            //     'name' => ucwords(str_replace('_', ' ', $key)),
            //     'email' => $key.'@app.com',
            //     'password' => bcrypt('password')
            // ]);

            // $user->attachRole($role);
        }
        $emp = App\Employee::firstOrCreate([
            'name' => 'superaadmin', 
            'phone' => '0987654321', 
            'address' => 'superadmin', 
            'salary' => 200000
        ]);

        $superadmin = App\User::firstOrCreate([
            'username' => 'super',
            'password' => bcrypt('123456'),
            'employee_id' => $emp->id,
        ]);

        $superadmin->attachRole('superadmin');
        // Creating user with permissions
        if (!empty($userPermission)) {

            // Create default user for each permission set
            // $user = \App\User::create([
            //     'username'  => 'super',
            //     'password'  => bcrypt('123456'),
            //     'email'     => 'test@test.com'
            // ]);
            // $user = \App\User::find(1);

            $permissions = [];
            foreach ($userPermission as $key => $modules) {

                foreach ($modules as $module => $value) {


                    foreach (explode(',', $value) as $p => $perm) {

                        $permissionValue = $mapPermission->get($perm);

                        $permissions[] = \App\Permission::firstOrCreate([
                            'name' => $module . '-' . $permissionValue,
                            'display_name' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                            'description' => ucfirst($permissionValue) . ' ' . ucfirst($module),
                        ])->id;

                        $this->command->info('Creating Permission to '.$permissionValue.' for '. $module);
                    }
                }

                // Attach all permissions to the user
                //$user->permissions()->sync($permissions);
            }
        }
    }

    /**
     * Truncates all the laratrust tables and the users table
     *
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        // Schema::disableForeignKeyConstraints();
        // DB::table('permission_role')->truncate();
        // DB::table('permission_user')->truncate();
        // DB::table('role_user')->truncate();
        // \App\User::truncate();
        // \App\Role::truncate();
        // \App\Permission::truncate();
        // Schema::enableForeignKeyConstraints();
    }
}