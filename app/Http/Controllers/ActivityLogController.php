<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ActivityLogController extends Controller
{
    public function download()
    {
        try {
            Log::info('Iniciando descarga de historial');
            
            $activities = ActivityLog::with('user')->orderBy('created_at', 'desc')->get();
            Log::info('Actividades recuperadas: ' . $activities->count());

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Establecer encabezados
            $sheet->setCellValue('A1', 'Fecha');
            $sheet->setCellValue('B1', 'Usuario');
            $sheet->setCellValue('C1', 'Acción');
            $sheet->setCellValue('D1', 'Descripción');
            $sheet->setCellValue('E1', 'Tabla');
            $sheet->setCellValue('F1', 'ID Registro');
            $sheet->setCellValue('G1', 'Datos Anteriores');
            $sheet->setCellValue('H1', 'Datos Nuevos');

            // Llenar datos
            $row = 2;
            foreach ($activities as $activity) {
                $sheet->setCellValue('A' . $row, $activity->created_at);
                $sheet->setCellValue('B' . $row, $activity->user ? $activity->user->email : 'Sistema');
                $sheet->setCellValue('C' . $row, $activity->action);
                $sheet->setCellValue('D' . $row, $activity->description);
                $sheet->setCellValue('E' . $row, $activity->table_name);
                $sheet->setCellValue('F' . $row, $activity->record_id);
                $sheet->setCellValue('G' . $row, json_encode($activity->old_data, JSON_PRETTY_PRINT));
                $sheet->setCellValue('H' . $row, json_encode($activity->new_data, JSON_PRETTY_PRINT));
                $row++;
            }

            // Ajustar ancho de columnas
            foreach (range('A', 'H') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Crear archivo temporal
            $fileName = 'historial_' . date('Y-m-d_H-i-s') . '.xlsx';
            $tempPath = storage_path('app/public/' . $fileName);

            $writer = new Xlsx($spreadsheet);
            $writer->save($tempPath);

            Log::info('Archivo de historial creado exitosamente: ' . $fileName);

            // Descargar y luego eliminar el archivo temporal
            return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error al generar el archivo de historial: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el archivo de historial'], 500);
        }
    }
}
