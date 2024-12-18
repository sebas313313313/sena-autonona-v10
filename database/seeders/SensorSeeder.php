<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensor;

class SensorSeeder extends Seeder
{
    public function run()
    {
        $sensores = [
            // Sensores para acuaponía
            ['description' => 'Sensor de pH del Agua', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Temperatura del Agua', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Oxígeno Disuelto', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Nivel de Agua', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Turbidez', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Amoniaco', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Nitritos', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Nitratos', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Temperatura Ambiente', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Humedad Ambiente', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de CO2', 'farm_type' => 'acuaponica'],
            ['description' => 'Sensor de Intensidad de Luz', 'farm_type' => 'acuaponica'],
            
            // Sensores para hidroponía
            ['description' => 'Sensor de pH de la Solución', 'farm_type' => 'hidroponica'],
            ['description' => 'Sensor de Conductividad Eléctrica (EC)', 'farm_type' => 'hidroponica'],
            ['description' => 'Sensor de Temperatura de Solución', 'farm_type' => 'hidroponica'],
            ['description' => 'Sensor de Nivel de Solución', 'farm_type' => 'hidroponica'],
            ['description' => 'Sensor de Flujo de Agua', 'farm_type' => 'hidroponica'],
            ['description' => 'Sensor TDS (Sólidos Disueltos Totales)', 'farm_type' => 'hidroponica'],
            ['description' => 'Sensor de Presión del Sistema', 'farm_type' => 'hidroponica'],
            ['description' => 'Sensor de Oxígeno en Raíz', 'farm_type' => 'hidroponica']
        ];

        foreach ($sensores as $sensor) {
            Sensor::create($sensor);
        }
    }
}
