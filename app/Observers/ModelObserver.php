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
            if (Auth::check()) {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'create',
                    'description' => 'Creó un nuevo registro',
                    'table_name' => $model->getTable(),
                    'record_id' => $model->id,
                    'old_data' => null,
                    'new_data' => $model->toArray()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar actividad (created): ' . $e->getMessage());
        }
    }

    public function updated(Model $model)
    {
        try {
            if (Auth::check()) {
                $oldData = array_intersect_key(
                    $model->getOriginal(),
                    $model->getDirty()
                );

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'update',
                    'description' => 'Actualizó un registro',
                    'table_name' => $model->getTable(),
                    'record_id' => $model->id,
                    'old_data' => $oldData,
                    'new_data' => $model->getDirty()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar actividad (updated): ' . $e->getMessage());
        }
    }

    public function deleted(Model $model)
    {
        try {
            if (Auth::check()) {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'delete',
                    'description' => 'Eliminó un registro',
                    'table_name' => $model->getTable(),
                    'record_id' => $model->id,
                    'old_data' => $model->toArray(),
                    'new_data' => null
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al registrar actividad (deleted): ' . $e->getMessage());
        }
    }
}
