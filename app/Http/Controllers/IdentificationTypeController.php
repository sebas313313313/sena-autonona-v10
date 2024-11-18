<?php

namespace App\Http\Controllers;

use App\Models\Identification_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IdentificationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $types = Identification_Type::filter($request->all())->get();
            return response()->json(['data' => $types]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener tipos de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudieron cargar los tipos de identificación. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'required|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $type = Identification_Type::create($request->all());
            return response()->json([
                'message' => 'Tipo de identificación creado exitosamente',
                'data' => $type
            ], 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al crear tipo de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo crear el tipo de identificación. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Identification_Type $identification_type)
    {
        try {
            return response()->json(['data' => $identification_type]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al obtener tipo de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo obtener el tipo de identificación. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Identification_Type $identification_type)
    {
        try {
            $validator = Validator::make($request->all(), [
                'description' => 'required|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $identification_type->update($request->all());
            return response()->json([
                'message' => 'Tipo de identificación actualizado exitosamente',
                'data' => $identification_type
            ]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al actualizar tipo de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo actualizar el tipo de identificación. Por favor, intente más tarde'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Identification_Type $identification_type)
    {
        try {
            $identification_type->delete();
            return response()->json(['message' => 'Tipo de identificación eliminado exitosamente']);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json([
                    'error' => 'Error al eliminar tipo de identificación',
                    'debug' => [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]
                ], 500);
            }
            return response()->json(['error' => 'No se pudo eliminar el tipo de identificación. Por favor, intente más tarde'], 500);
        }
    }
}
