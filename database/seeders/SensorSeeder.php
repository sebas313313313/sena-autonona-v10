<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Farm;
use App\Models\Sensor;
use App\Models\Component;
use App\Models\Farm_Component;
use App\Models\Sensor_Component;

class SensorSeeder extends Seeder
{
    public function run($farm = null, $selectedSensors = [])
    {
        if (!$farm) {
            return;
        }

        // Obtener o crear el componente basado en el tipo de granja
        $componentName = match ($farm->farm_type) {
            'acuaponica' => 'Acuaponia',
            'hidroponica' => 'Hidroponia',
            'vigilancia' => 'Sistema de Vigilancia',
            'riego' => 'Sistema de Riego',
        };

        $component = Component::firstOrCreate(
            ['description' => $componentName]
        );

        // Crear o encontrar el farm_component
        $farmComponent = Farm_Component::firstOrCreate(
            [
                'farm_id' => $farm->id,
                'component_id' => $component->id,
            ],
            [
                'description' => 'Componente de ' . $farm->name
            ]
        );

        // Crear sensores solo para los sensores seleccionados
        foreach ($selectedSensors as $sensorName) {
            $sensor = Sensor::firstOrCreate(
                ['description' => $sensorName],
                [
                    'description' => $sensorName,
                    'unit' => $this->getSensorUnit($sensorName),
                    'type' => $farm->farm_type
                ]
            );

            // Asociar el sensor con el farm_component
            Sensor_Component::firstOrCreate(
                [
                    'sensor_id' => $sensor->id,
                    'farm_component_id' => $farmComponent->id,
                ],
                [
                    'min' => $this->getMinValue($sensorName),
                    'max' => $this->getMaxValue($sensorName)
                ]
            );
        }
    }

    private function getSensorUnit($sensorName)
    {
        return match ($sensorName) {
            'Sensor de Temperatura y Humedad', 
            'Sensor Temperatura Ambiente Alta Resolución',
            'Sensor de Temperatura del Suelo' => '°C',
            
            'Sensor de Humedad en Tierra',
            'Sensor de Humedad del Suelo',
            'Sensor de Lluvia' => '%',
            
            'Sensor de pH' => 'pH',
            'Sensor de Conductividad Eléctrica/CE' => 'mS/cm',
            'Sensor Nivel de Líquidos',
            'Sensor de Nivel de Agua' => '%',
            'Sensor de Oxígeno Disuelto' => 'mg/L',
            
            'Sensor Fotorresistor' => 'lux',
            'Sensor de Radiación Solar' => 'W/m²',
            
            'Evaporímetro' => 'mm/día',
            'Sensor de Amonio/Nitrito/Nitrato' => 'ppm',
            
            'Sensor de Flujo de Agua' => 'L/min',
            'Sensor de Presión de Agua' => 'bar',
            
            'Sensor de Movimiento PIR',
            'Sensor de Presencia',
            'Sensor Magnético de Puerta/Ventana',
            'Sensor de Rotura de Cristal',
            'Sensor de Inundación' => 'binario',
            
            'Sensor de Humo',
            'Sensor de Gas' => 'ppm',
            'Sensor de Vibración' => 'intensidad',
            
            default => 'unidad'
        };
    }

    private function getMinValue($sensorName)
    {
        return match ($sensorName) {
            'Sensor de Temperatura y Humedad', 
            'Sensor Temperatura Ambiente Alta Resolución' => 18,
            'Sensor de Temperatura del Suelo' => 15,
            
            'Sensor de Humedad en Tierra',
            'Sensor de Humedad del Suelo' => 30,
            'Sensor de Lluvia' => 0,
            
            'Sensor de pH' => 55, // 5.5
            'Sensor de Conductividad Eléctrica/CE' => 5, // 0.5
            'Sensor Nivel de Líquidos',
            'Sensor de Nivel de Agua' => 0,
            'Sensor de Oxígeno Disuelto' => 50, // 5.0
            
            'Sensor Fotorresistor' => 100,
            'Sensor de Radiación Solar' => 0,
            
            'Evaporímetro' => 0,
            'Sensor de Amonio/Nitrito/Nitrato' => 0,
            
            'Sensor de Flujo de Agua' => 100, // 10.0
            'Sensor de Presión de Agua' => 10, // 1.0
            
            default => 0
        };
    }

    private function getMaxValue($sensorName)
    {
        return match ($sensorName) {
            'Sensor de Temperatura y Humedad', 
            'Sensor Temperatura Ambiente Alta Resolución' => 32,
            'Sensor de Temperatura del Suelo' => 30,
            
            'Sensor de Humedad en Tierra',
            'Sensor de Humedad del Suelo' => 90,
            'Sensor de Lluvia' => 100,
            
            'Sensor de pH' => 75, // 7.5
            'Sensor de Conductividad Eléctrica/CE' => 30, // 3.0
            'Sensor Nivel de Líquidos',
            'Sensor de Nivel de Agua' => 100,
            'Sensor de Oxígeno Disuelto' => 90, // 9.0
            
            'Sensor Fotorresistor' => 10000,
            'Sensor de Radiación Solar' => 1200,
            
            'Evaporímetro' => 100,
            'Sensor de Amonio/Nitrito/Nitrato' => 50,
            
            'Sensor de Flujo de Agua' => 2000, // 200.0
            'Sensor de Presión de Agua' => 60, // 6.0
            
            default => 100
        };
    }
}
