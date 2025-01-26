<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function download()
    {
        try {
            Log::info('Iniciando descarga de historial');
            
            // Verificar si la tabla existe
            if (!Schema::hasTable('activity_logs')) {
                Log::error('La tabla activity_logs no existe');
                return response()->json(['error' => 'La tabla de actividades no existe'], 500);
            }
            
            // Obtener todas las actividades con la relación de usuario
            $activities = ActivityLog::with('user')->orderBy('created_at', 'desc')->get();
            Log::info('Actividades recuperadas: ' . $activities->count());

            if ($activities->count() == 0) {
                Log::warning('No se encontraron registros de actividad');
                return response()->json(['error' => 'No hay registros de actividad para exportar'], 404);
            }

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Establecer encabezados y estilos
            $headers = ['Fecha', 'Usuario', 'Acción', 'Descripción', 'Tabla', 'ID Registro', 'Datos Anteriores', 'Datos Nuevos'];
            foreach (array_values($headers) as $index => $header) {
                $column = chr(65 + $index);
                $sheet->setCellValue($column . '1', $header);
                // Estilo para encabezados
                $sheet->getStyle($column . '1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'E2EFDA']
                    ]
                ]);
            }

            // Llenar datos
            $row = 2;
            foreach ($activities as $activity) {
                Log::debug('Procesando actividad ID: ' . $activity->id);
                
                $sheet->setCellValue('A' . $row, $activity->created_at ? $activity->created_at->format('Y-m-d H:i:s') : '');
                $sheet->setCellValue('B' . $row, $activity->user ? $activity->user->email : 'Sistema');
                $sheet->setCellValue('C' . $row, ucfirst($activity->action ?? ''));
                $sheet->setCellValue('D' . $row, $activity->description ?? '');
                $sheet->setCellValue('E' . $row, ucfirst($activity->table_name ?? ''));
                $sheet->setCellValue('F' . $row, $activity->record_id ?? '');
                
                // Formatear datos JSON para mejor legibilidad
                $oldData = $this->formatJsonData($activity->old_data);
                $newData = $this->formatJsonData($activity->new_data);
                
                $sheet->setCellValue('G' . $row, $oldData);
                $sheet->setCellValue('H' . $row, $newData);
                
                // Aplicar bordes a la fila
                $sheet->getStyle('A'.$row.':H'.$row)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ]
                    ]
                ]);
                
                $row++;
            }

            // Ajustar ancho de columnas
            foreach (range('A', 'H') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Aplicar formato de texto y estilos para las columnas de datos JSON
            $lastRow = $row - 1;
            $sheet->getStyle('G2:H' . $lastRow)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ]);

            // Alternar colores de fondo para mejor legibilidad
            for ($i = 2; $i <= $lastRow; $i++) {
                if ($i % 2 == 0) {
                    $sheet->getStyle('A'.$i.':H'.$i)->applyFromArray([
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F5F5F5']
                        ]
                    ]);
                }
            }

            // Crear archivo temporal
            $fileName = 'historial_' . date('Y-m-d_H-i-s') . '.xlsx';
            $tempPath = storage_path('app/public/' . $fileName);
            
            Log::info('Guardando archivo en: ' . $tempPath);

            $writer = new Xlsx($spreadsheet);
            $writer->save($tempPath);

            if (!file_exists($tempPath)) {
                Log::error('El archivo no se creó correctamente en: ' . $tempPath);
                return response()->json(['error' => 'Error al generar el archivo'], 500);
            }

            Log::info('Archivo de historial creado exitosamente: ' . $fileName);

            // Descargar y luego eliminar el archivo temporal
            return response()->download($tempPath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error al generar el archivo de historial: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Error al generar el archivo de historial: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Formatea los datos JSON para una mejor presentación
     */
    private function formatJsonData($data)
    {
        if (empty($data)) {
            return '';
        }

        if (!is_array($data)) {
            return $data;
        }

        $output = [];
        foreach ($data as $key => $value) {
            if ($key === 'password' || $key === 'remember_token') {
                continue; // Saltar datos sensibles
            }
            
            // Formatear fechas
            if (in_array($key, ['created_at', 'updated_at', 'email_verified_at']) && !empty($value)) {
                $value = date('Y-m-d H:i:s', strtotime($value));
            }
            
            // Formatear valores booleanos
            if (is_bool($value)) {
                $value = $value ? 'Sí' : 'No';
            }
            
            $output[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
        }

        return implode("\n", $output);
    }
}
