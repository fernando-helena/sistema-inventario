<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    // Mostrar todos los productos
    public function index()
    {
        return response()->json(Producto::all(), 200);
    }

    // Mostrar un producto
    public function show($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'mensaje' => 'Producto no encontrado'
            ], 404);
        }

        return response()->json($producto, 200);
    }

    // Registrar producto
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|unique:productos,nombre',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        $producto = Producto::create($datos);

        return response()->json($producto, 201);
    }

    // Actualizar producto
    public function update(Request $request, $id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'mensaje' => 'Producto no encontrado'
            ], 404);
        }

        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                Rule::unique('productos', 'nombre')->ignore($producto->id)
            ],
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
        ]);

        $producto->update($datos);

        return response()->json($producto, 200);
    }

    // Eliminar producto
    public function destroy($id)
    {
        $producto = Producto::find($id);

        if (!$producto) {
            return response()->json([
                'mensaje' => 'Producto no encontrado'
            ], 404);
        }

        $producto->delete();

        return response()->noContent();
    }
}