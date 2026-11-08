<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Laptop HP',
                'descripcion' => 'Laptop HP con 8GB de RAM',
                'precio' => 650.00,
                'stock' => 10
            ],
            [
                'nombre' => 'Mouse Inalambrico',
                'descripcion' => 'Mouse USB inalambrico',
                'precio' => 15.50,
                'stock' => 25
            ],
            [
                'nombre' => 'Teclado Mecanico',
                'descripcion' => 'Teclado mecanico RGB',
                'precio' => 45.00,
                'stock' => 12
            ],
            [
                'nombre' => 'Monitor Samsung',
                'descripcion' => 'Monitor LED de 24 pulgadas',
                'precio' => 180.00,
                'stock' => 8
            ],
            [
                'nombre' => 'Audifonos Bluetooth',
                'descripcion' => 'Audifonos inalambricos',
                'precio' => 30.00,
                'stock' => 0
            ]
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}