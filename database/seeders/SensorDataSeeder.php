<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensor;
use App\Models\Sensor_Component;
use App\Models\Sample;
use App\Models\Calibration;
use App\Models\Farm;
use App\Models\Farm_Component;
use App\Models\Component;
use Carbon\Carbon;

class SensorDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear una granja de prueba
        $farm = Farm::create([
            'name' => 'Granja de Prueba',
            'farm_type' => 'hidroponica',
            'latitude' => 4.570868,
            'longitude' => -74.297333,
            'address' => 'Carrera 123 #45-67',
            'vereda' => 'Vereda El Progreso',
            'extension' => '1000',
            'users_role_id' => 1, // Asegúrate de que este ID existe
            'municipality_id' => 1 // Asegúrate de que este ID existe
        ]);

        // Crear componentes base
        $components = [
            'Invernadero principal',
            'Invernadero secundario',
            'Sistema de riego automatizado',
            'Sistema de control ambiental',
            'Área de almacenamiento'
        ];

        $farmComponentIds = [];
        foreach ($components as $componentDescription) {
            $component = Component::create([
                'description' => $componentDescription
            ]);

            $farmComponent = Farm_Component::create([
                'farm_id' => $farm->id,
                'component_id' => $component->id,
                'description' => 'Componente activo y funcionando correctamente'
            ]);

            $farmComponentIds[] = $farmComponent->id;
        }

        // Crear tipos de sensores
        $sensorTypes = [
            [
                'description' => 'Sensor de Temperatura',
                'components' => [
                    ['min' => 15, 'max' => 35], // Temperatura en °C
                    ['min' => 18, 'max' => 30],
                ]
            ],
            [
                'description' => 'Sensor de Humedad',
                'components' => [
                    ['min' => 40, 'max' => 80], // Humedad en %
                    ['min' => 50, 'max' => 75],
                ]
            ],
            [
                'description' => 'Sensor de pH',
                'components' => [
                    ['min' => 5.5, 'max' => 7.5], // Niveles de pH
                    ['min' => 6.0, 'max' => 7.0],
                ]
            ],
            [
                'description' => 'Sensor de Luminosidad',
                'components' => [
                    ['min' => 1000, 'max' => 10000], // Lux
                    ['min' => 2000, 'max' => 8000],
                ]
            ]
        ];

        foreach ($sensorTypes as $sensorType) {
            $sensor = Sensor::create([
                'description' => $sensorType['description'],
                'farm_type' => 'hidroponica'
            ]);

            // Crear componentes para cada sensor
            foreach ($sensorType['components'] as $component) {
                $sensorComponent = Sensor_Component::create([
                    'sensor_id' => $sensor->id,
                    'farm_component_id' => $farmComponentIds[array_rand($farmComponentIds)],
                    'min' => $component['min'],
                    'max' => $component['max']
                ]);

                // Crear calibración para cada componente
                Calibration::create([
                    'sensor_component_id' => $sensorComponent->id,
                    'date' => Carbon::now(),
                    'parameters' => rand(1, 100),
                    'alert' => rand(0, 1)
                ]);

                // Crear muestras históricas para los últimos 7 días
                for ($i = 0; $i < 168; $i++) { // 24 lecturas * 7 días
                    $timestamp = Carbon::now()->subHours($i);
                    
                    // Generar un valor aleatorio dentro del rango min-max
                    // Multiplicamos por 100 para mantener 2 decimales de precisión en enteros
                    $value = rand(
                        (int)($component['min'] * 100), 
                        (int)($component['max'] * 100)
                    );

                    Sample::create([
                        'sensor_component_id' => $sensorComponent->id,
                        'fecha_hora' => $timestamp,
                        'value' => $value
                    ]);
                }
            }
        }
    }
}
