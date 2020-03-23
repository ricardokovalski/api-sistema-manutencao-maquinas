<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'Administrador',
                'guard_name' => 'api',
            ],
            [
                'name' => 'Funcionário',
                'guard_name' => 'api',
            ],
            [
                'name' => 'Responsável Técnico',
                'guard_name' => 'api',
            ],
            [
                'name' => 'Visitante',
                'guard_name' => 'api',
            ],
        ];

        foreach ($roles as $role) {
            \Spatie\Permission\Models\Role::create($role);
        }
    }
}
