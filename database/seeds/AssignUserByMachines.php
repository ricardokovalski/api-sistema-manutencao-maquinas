<?php

use Illuminate\Database\Seeder;

class AssignUserByMachines extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = \Spatie\Permission\Models\Role::findById(3);

        //Technical Manager
        $users = \App\Entities\User::role($role->name)
            ->pluck('id')
            ->toArray();

        $machines = \App\Entities\Machine::all();

        foreach ($machines as $machine) {
            $user = $users[array_rand($users, 1)];
            $machine->users()->attach($user);
        }
    }
}
