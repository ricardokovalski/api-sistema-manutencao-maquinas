<?php

use Illuminate\Database\Seeder;

class AssignMaintenanceByPeaces extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $peaces = \App\Entities\Peace::all()
            ->pluck('id')
            ->toArray();

        $collectionMaintenance = \App\Entities\Maintenance::all();

        foreach ($collectionMaintenance as $maintenance) {
            $peace = $peaces[array_rand($peaces, 1)];
            $maintenance->peaces()->attach($peace, [
                'amount_used' => rand(1, 10),
            ]);
        }
    }
}
