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
            $sensor = Sensor::create([
                'description' => $sensorName,
                'farm_type' => $farm->farm_type,
                'estado' => 'activo'
            ]);

            // Crear la relación sensor_component
            Sensor_Component::firstOrCreate(
                [
                    'sensor_id' => $sensor->id,
                    'farm_component_id' => $farmComponent->id
                ],
                [
                    'min' => $this->getMinValue($sensorName),
                    'max' => $this->getMaxValue($sensorName)
                ]
            );
        }
    }

    private function getMinValue($sensorName)
    {
        return match($sensorName) {
            'Sensor de Temperatura y Humedad' => 0,
            'Sensor de pH' => 0,
            'Sensor de Conductividad Eléctrica/CE' => 0,
            'Sensor de Oxígeno Disuelto' => 0,
            'Sensor de Nivel de Agua' => 0,
            'Sensor de Movimiento PIR' => 0,
            'Cámara con Visión Nocturna' => 0,
            'Sensor de Flujo de Agua' => 0,
            'Sensor de Presión de Agua' => 0,
            default => 0,
        };
    }

    private function getMaxValue($sensorName)
    {
        return match($sensorName) {
            'Sensor de Temperatura y Humedad' => 100,
            'Sensor de pH' => 14,
            'Sensor de Conductividad Eléctrica/CE' => 5000,
            'Sensor de Oxígeno Disuelto' => 20,
            'Sensor de Nivel de Agua' => 100,
            'Sensor de Movimiento PIR' => 1,
            'Cámara con Visión Nocturna' => 1,
            'Sensor de Flujo de Agua' => 100,
            'Sensor de Presión de Agua' => 100,
            default => 100,
        };
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
}
