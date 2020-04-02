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

        if (config('database.default') == 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        if (config('database.default') == 'pgsql') {
            $tables = array_map('reset', \DB::select('SHOW TABLES'));
            foreach($tables as $table) {
                \DB::statement("ALTER {$table} DISABLE TRIGGER ALL;");
            }
        }

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

        if (config('database.default') == 'mysql') {
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        if (config('database.default') == 'pgsql') {
            $tables = array_map('reset', \DB::select('SHOW TABLES'));
            foreach($tables as $table) {
                \DB::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");
            }
        }
    }
}
