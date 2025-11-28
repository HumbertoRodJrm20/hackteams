@extends('Layout.app') {{-- CORRECCIÓN: Usando 'Layout' con L mayúscula --}}

@section('nav_eventos', 'active')
@section('title', 'Eventos Activos')


@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-broadcast fs-2 me-3 text-primary"></i>
        <h1 class="fw-bold mb-0">Eventos</h1>
    </div>

    <div class="row mb-4 align-items-center">
        <div class="col-md-3">
            <div class="d-grid">
                <a href="{{ route('eventos.crear') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Crear evento
                </a>
            </div>
        </div>
    </div>

    <hr>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        
        @php
            $eventos = [/* ... */];
        @endphp

        @foreach ($eventos as $evento)
        <div class="col">
            <div class="card card-evento h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center pt-2">
                        {{-- Botón Leer Más (Enlace a Detalle) --}}
                        <a href="{{ route('eventos.info') }}" class="btn btn-link text-info fw-bold p-0">
                            LEER MÁS
                        </a>
                        
                        {{-- Botón Eliminar --}}
                        <form action="{{ route('eventos.index') }}" method="POST" onsubmit="return confirm('¿Eliminar evento?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger fw-bold p-0">
                                ELIMINAR
                            </button>
                        </form>
                    </div>
                </div>
                </div>
        </div>
        @endforeach
        
    </div>
</div>
@endsection