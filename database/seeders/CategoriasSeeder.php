<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriasSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Aplicación Web',
                'descripcion' => 'Proyectos de aplicaciones web desarrolladas con tecnologías modernas',
                'icono' => 'bi-globe',
                'color' => '#667eea',
            ],
            [
                'nombre' => 'Aplicación Móvil',
                'descripcion' => 'Aplicaciones para dispositivos móviles (iOS, Android)',
                'icono' => 'bi-phone',
                'color' => '#764ba2',
            ],
            [
                'nombre' => 'Inteligencia Artificial',
                'descripcion' => 'Proyectos que utilizan IA, Machine Learning o Deep Learning',
                'icono' => 'bi-robot',
                'color' => '#f093fb',
            ],
            [
                'nombre' => 'Internet de las Cosas (IoT)',
                'descripcion' => 'Proyectos de hardware conectado y sistemas embebidos',
                'icono' => 'bi-cpu',
                'color' => '#4facfe',
            ],
            [
                'nombre' => 'Videojuegos',
                'descripcion' => 'Desarrollo de videojuegos y experiencias interactivas',
                'icono' => 'bi-controller',
                'color' => '#43e97b',
            ],
            [
                'nombre' => 'Blockchain',
                'descripcion' => 'Proyectos basados en tecnología blockchain y criptomonedas',
                'icono' => 'bi-link-45deg',
                'color' => '#fa709a',
            ],
            [
                'nombre' => 'Ciberseguridad',
                'descripcion' => 'Herramientas y soluciones de seguridad informática',
                'icono' => 'bi-shield-check',
                'color' => '#e74c3c',
            ],
            [
                'nombre' => 'Realidad Virtual/Aumentada',
                'descripcion' => 'Experiencias inmersivas en VR o AR',
                'icono' => 'bi-badge-vr',
                'color' => '#9b59b6',
            ],
            [
                'nombre' => 'Educación',
                'descripcion' => 'Plataformas y herramientas educativas',
                'icono' => 'bi-book',
                'color' => '#3498db',
            ],
            [
                'nombre' => 'Salud',
                'descripcion' => 'Soluciones tecnológicas para el sector salud',
                'icono' => 'bi-heart-pulse',
                'color' => '#e74c3c',
            ],
            [
                'nombre' => 'Sostenibilidad',
                'descripcion' => 'Proyectos enfocados en medio ambiente y sostenibilidad',
                'icono' => 'bi-tree',
                'color' => '#27ae60',
            ],
            [
                'nombre' => 'Fintech',
                'descripcion' => 'Tecnología financiera e innovación en servicios financieros',
                'icono' => 'bi-currency-dollar',
                'color' => '#f39c12',
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
