<?php

use Illuminate\Database\Seeder;

class AssignRoleByUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = \Spatie\Permission\Models\Role::all()
            ->pluck('name')
            ->toArray();

        $users = \App\Entities\User::all();

        foreach ($users as $user) {
            $user->assignRole($roles[array_rand($roles, 1)]);
        }
    }
}
