<?php

use Illuminate\Database\Seeder;

class ClearTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $machines = \App\Entities\Machine::all();

        foreach ($machines as $machine) {
            $machine->pieces()->detach();
            $machine->users()->detach();
        }

        $collectionMaintenance = \App\Entities\Maintenance::all();

        foreach ($collectionMaintenance as $maintenance) {
            $maintenance->pieces()->detach();
        }

        $users = \App\Entities\User::all();
        $roles = \Spatie\Permission\Models\Role::all()
            ->pluck('name')
            ->toArray();

        foreach ($users as $user) {
            foreach ($roles as $role) {
                if ($user->hasRole($role)) {
                    $user->removeRole($role);
                }
            }
        }

        \DB::table('machines')->delete();
        \DB::table('maintenance')->delete();
        \DB::table('peaces')->delete();
        \DB::table('users')->delete();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
