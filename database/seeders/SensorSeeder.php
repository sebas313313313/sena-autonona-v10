<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Component;
use App\Models\Farm;
use App\Models\Farm_Component;
use App\Models\Sensor;

class SensorSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tablas
        DB::table('sensor_components')->truncate();
        DB::table('sensors')->truncate();

        // Crear una granja de prueba
        $farm = Farm::create([
            'name' => 'Granja de Prueba',
            'farm_type' => 'hidroponica',
            'latitude' => 4.570868,
            'longitude' => -74.297333,
            'address' => 'Carrera 123 #45-67',
            'vereda' => 'Vereda El Progreso',
            'extension' => '1000',
            'users_role_id' => 1,
            'municipality_id' => 1
        ]);

        // Definir los sensores por tipo
        $sensorsByType = [
            'acuaponica' => [
                'esenciales' => [
                    'Sensor de Humedad en Tierra',
                    'Sensor Nivel de Líquidos',
                    'Sensor de Temperatura y Humedad',
                    'Sensor Fotorresistor',
                    'Sensor de pH',
                    'Sensor Temperatura Ambiente Alta Resolución',
                    'Sensor de Oxígeno Disuelto',
                    'Sensor de Amonio/Nitrito/Nitrato'
                ],
                'utiles' => [
                    'Display OLED',
                    'Joystick',
                    'Sensor Ultrasónico',
                    'LED RGB',
                    'Servo Motor'
                ]
            ],
            'hidroponica' => [
                'esenciales' => [
                    'Sensor de Humedad en Tierra',
                    'Sensor Nivel de Líquidos',
                    'Sensor de Temperatura y Humedad',
                    'Sensor Fotorresistor',
                    'Sensor de pH',
                    'Sensor Temperatura Ambiente Alta Resolución',
                    'Sensor de Conductividad Eléctrica/CE'
                ],
                'utiles' => [
                    'Display OLED',
                    'Joystick',
                    'Sensor Ultrasónico',
                    'LED RGB',
                    'Servo Motor'
                ]
            ],
            'vigilancia' => [
                'esenciales' => [
                    'Sensor de Movimiento PIR',
                    'Sensor de Presencia',
                    'Cámara con Visión Nocturna',
                    'Sensor Magnético de Puerta/Ventana',
                    'Sensor de Rotura de Cristal'
                ],
                'utiles' => [
                    'Sensor de Humo',
                    'Sensor de Gas',
                    'Sensor de Inundación',
                    'Sensor de Vibración',
                    'Micrófono'
                ]
            ],
            'riego' => [
                'esenciales' => [
                    'Sensor de Humedad del Suelo',
                    'Sensor de Lluvia',
                    'Sensor de Nivel de Agua'
                ],
                'utiles' => [
                    'Sensor de Flujo de Agua',
                    'Sensor de Presión de Agua',
                    'Sensor de Temperatura del Suelo',
                    'Evaporímetro',
                    'Sensor de Radiación Solar'
                ]
            ]
        ];

        // Crear sensores y asociaciones
        foreach ($sensorsByType as $farmType => $sensorCategories) {
            foreach ($sensorCategories as $category => $sensors) {
                foreach ($sensors as $sensorName) {
                    // Crear o encontrar el sensor
                    $sensor = Sensor::firstOrCreate(
                        ['description' => $sensorName],
                        ['farm_type' => $farmType]
                    );

                    // Obtener el componente correspondiente
                    $componentName = match ($farmType) {
                        'acuaponica' => 'Acuaponia',
                        'hidroponica' => 'Hidroponia',
                        'vigilancia' => 'Sistema de Vigilancia',
                        'riego' => 'Sistema de Riego',
                    };
                    
                    $component = Component::where('description', $componentName)->first();

                    if ($component) {
                        // Crear farm_component si no existe
                        $farmComponent = Farm_Component::firstOrCreate(
                            [
                                'farm_id' => $farm->id,
                                'component_id' => $component->id,
                            ],
                            [
                                'description' => 'Componente de prueba - ' . $componentName
                            ]
                        );

                        // Crear la asociación en sensor_components
                        DB::table('sensor_components')->insert([
                            'sensor_id' => $sensor->id,
                            'farm_component_id' => $farmComponent->id,
                            'min' => 0,
                            'max' => 100,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }
        }
    }
}
