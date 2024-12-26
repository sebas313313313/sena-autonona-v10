<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sample;
use App\Models\Sensor_Component;
use Carbon\Carbon;

class SampleSeeder extends Seeder
{
    public function run()
    {
        // Limpiar tabla de muestras
        Sample::truncate();

        // Obtener todos los componentes de sensores
        $sensorComponents = Sensor_Component::with(['sensor', 'farmComponent'])->get();

        foreach ($sensorComponents as $sensorComponent) {
            // Generar datos para las últimas 24 horas
            $startTime = Carbon::now()->subHours(24);
            
            // Generar una lectura cada hora
            for ($i = 0; $i < 24; $i++) {
                $timestamp = $startTime->copy()->addHours($i);
                
                // Generar un valor aleatorio entre el mínimo y máximo del sensor
                $value = rand($sensorComponent->min, $sensorComponent->max);
                
                Sample::create([
                    'sensor_component_id' => $sensorComponent->id,
                    'fecha_hora' => $timestamp,
                    'value' => $value
                ]);
            }
        }
    }
}
