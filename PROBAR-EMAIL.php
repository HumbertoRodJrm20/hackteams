<?php

/**
 * Script de prueba para verificar envío de emails
 * Ejecutar: php PROBAR-EMAIL.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Mail\InvitacionEquipoMail;
use App\Models\SolicitudEquipo;
use Illuminate\Support\Facades\Mail;

echo "=== PRUEBA DE ENVÍO DE EMAIL ===\n\n";

try {
    // Buscar una invitación de prueba (la más reciente)
    $invitacion = SolicitudEquipo::where('tipo', 'invitacion')
        ->orderBy('created_at', 'desc')
        ->first();

    if (! $invitacion) {
        echo "❌ No se encontró ninguna invitación en la base de datos.\n";
        echo "   Intenta crear una invitación primero desde la aplicación.\n";
        exit(1);
    }

    echo "✓ Invitación encontrada (ID: {$invitacion->id})\n";
    echo "  Equipo: {$invitacion->equipo->nombre}\n";
    echo "  Participante: {$invitacion->participante->user->email}\n\n";

    // Intentar enviar el email
    echo "Enviando email de prueba...\n";

    Mail::to($invitacion->participante->user->email)->send(
        new InvitacionEquipoMail($invitacion)
    );

    echo "\n✅ EMAIL ENVIADO EXITOSAMENTE!\n";
    echo "   Revisa la bandeja de entrada de: {$invitacion->participante->user->email}\n";

} catch (\Exception $e) {
    echo "\n❌ ERROR AL ENVIAR EMAIL:\n";
    echo "   {$e->getMessage()}\n\n";
    echo "Stack trace:\n{$e->getTraceAsString()}\n";
    exit(1);
}

echo "\n=== FIN DE LA PRUEBA ===\n";
