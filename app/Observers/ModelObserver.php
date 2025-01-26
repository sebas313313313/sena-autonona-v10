<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModelObserver
{
    public function created(Model $model)
    {
        try {
            Log::info('Intentando registrar actividad de creación para: ' . get_class($model));
            
            if (Auth::check()) {
                Log::info('Usuario autenticado: ' . Auth::id());
                
                $activityLog = ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'create',
                    'description' => 'Se creó un nuevo registro en ' . $model->getTable(),
                    'table_name' => $model->getTable(),
                    'record_id' => $model->id,
                    'old_data' => null,
                    'new_data' => $model->toArray()
                ]);
                
                Log::info('Actividad registrada con ID: ' . $activityLog->id);
            } else {
                Log::warning('No hay usuario autenticado para registrar la actividad');
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar actividad (created): ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    public function updated(Model $model)
    {
        try {
            Log::info('Intentando registrar actividad de actualización para: ' . get_class($model));
            
            if (Auth::check()) {
                Log::info('Usuario autenticado: ' . Auth::id());
                
                $oldData = array_intersect_key(
                    $model->getOriginal(),
                    $model->getDirty()
                );

                $activityLog = ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'update',
                    'description' => 'Se actualizó un registro en ' . $model->getTable(),
                    'table_name' => $model->getTable(),
                    'record_id' => $model->id,
                    'old_data' => $oldData,
                    'new_data' => $model->getDirty()
                ]);
                
                Log::info('Actividad registrada con ID: ' . $activityLog->id);
            } else {
                Log::warning('No hay usuario autenticado para registrar la actividad');
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar actividad (updated): ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    public function deleted(Model $model)
    {
        try {
            Log::info('Intentando registrar actividad de eliminación para: ' . get_class($model));
            
            if (Auth::check()) {
                Log::info('Usuario autenticado: ' . Auth::id());
                
                $activityLog = ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'delete',
                    'description' => 'Se eliminó un registro de ' . $model->getTable(),
                    'table_name' => $model->getTable(),
                    'record_id' => $model->id,
                    'old_data' => $model->toArray(),
                    'new_data' => null
                ]);
                
                Log::info('Actividad registrada con ID: ' . $activityLog->id);
            } else {
                Log::warning('No hay usuario autenticado para registrar la actividad');
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar actividad (deleted): ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}
