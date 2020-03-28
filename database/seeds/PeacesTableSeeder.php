<?php

use Illuminate\Database\Seeder;

class PeacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Entities\Peace::class, 500)->create();
    }
}
