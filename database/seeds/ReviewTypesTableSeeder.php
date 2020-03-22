<?php

use Illuminate\Database\Seeder;

class ReviewTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reviewTypes = [
            [
                'name' => 'Corretiva'
            ],
            [
                'name' => 'Preventiva'
            ]
        ];

        foreach ($reviewTypes as $reviewType) {
            \App\Entities\ReviewType::create($reviewType);
        }
    }
}
