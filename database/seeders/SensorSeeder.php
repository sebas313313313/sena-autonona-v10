<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensor;

class SensorSeeder extends Seeder
{
    public function run()
    {
        $sensors = [
            [
                'description' => 'Sensor de Temperatura',
                'farm_type' => 'hidroponica'
            ],
            [
                'description' => 'Sensor de Humedad',
                'farm_type' => 'hidroponica'
            ],
            [
                'description' => 'Sensor de pH',
                'farm_type' => 'hidroponica'
            ],
            [
                'description' => 'Sensor de CO2',
                'farm_type' => 'hidroponica'
            ],
            [
                'description' => 'Sensor de Luz',
                'farm_type' => 'hidroponica'
            ]
        ];

        foreach ($sensors as $sensor) {
            Sensor::create($sensor);
        }
    }
}
