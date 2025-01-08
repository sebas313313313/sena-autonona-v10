<?php

namespace App\Http\Controllers;

use App\Models\SecurityQuestion;
use Illuminate\Http\Request;

/**
 * Controlador para manejar las preguntas de seguridad
 * 
 * Este controlador gestiona las operaciones CRUD para las preguntas de seguridad
 * que se utilizan en el proceso de recuperación de contraseña.
 */
class SecurityQuestionController extends Controller
{
    /**
     * Obtiene todas las preguntas de seguridad
     * 
     * @return \Illuminate\Http\JsonResponse Lista de preguntas de seguridad en formato JSON
     */
    public function index()
    {
        $questions = SecurityQuestion::all();
        return response()->json($questions);
    }

    /**
     * Almacena una nueva pregunta de seguridad
     * 
     * @param Request $request Datos de la nueva pregunta
     * @return \Illuminate\Http\JsonResponse Respuesta con el estado de la operación y los datos de la pregunta creada
     */
    public function store(Request $request)
    {
        // Validar que la pregunta sea requerida, string, máximo 250 caracteres y única
        $request->validate([
            'question' => 'required|string|max:250|unique:security_questions,question'
        ]);

        // Crear la nueva pregunta de seguridad
        $question = SecurityQuestion::create([
            'question' => $request->question
        ]);

        // Retornar respuesta exitosa
        return response()->json([
            'success' => true,
            'message' => 'Pregunta de seguridad creada exitosamente',
            'data' => $question
        ]);
    }

    /**
     * Actualiza una pregunta de seguridad existente
     * 
     * @param Request $request Datos actualizados de la pregunta
     * @param SecurityQuestion $question Pregunta a actualizar
     * @return \Illuminate\Http\JsonResponse Respuesta con el estado de la operación y los datos actualizados
     */
    public function update(Request $request, SecurityQuestion $question)
    {
        // Validar que la pregunta sea requerida, string, máximo 250 caracteres y única (excepto para la pregunta actual)
        $request->validate([
            'question' => 'required|string|max:250|unique:security_questions,question,' . $question->id
        ]);

        // Actualizar la pregunta
        $question->update([
            'question' => $request->question
        ]);

        // Retornar respuesta exitosa
        return response()->json([
            'success' => true,
            'message' => 'Pregunta de seguridad actualizada exitosamente',
            'data' => $question
        ]);
    }

    /**
     * Elimina una pregunta de seguridad
     * 
     * @param SecurityQuestion $question Pregunta a eliminar
     * @return \Illuminate\Http\JsonResponse Respuesta con el estado de la operación
     */
    public function destroy(SecurityQuestion $question)
    {
        // Eliminar la pregunta
        $question->delete();

        // Retornar respuesta exitosa
        return response()->json([
            'success' => true,
            'message' => 'Pregunta de seguridad eliminada exitosamente'
        ]);
    }
}
