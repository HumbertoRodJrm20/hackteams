@echo off
echo ========================================
echo LIMPIANDO TODOS LOS CACHES DE LARAVEL
echo ========================================
echo.

REM Limpiar vistas compiladas (IMPORTANTE - soluciona el timeout)
echo [1/6] Limpiando vistas compiladas...
php artisan view:clear
if errorlevel 1 (
    echo ERROR: No se pudo limpiar vistas
    pause
    exit /b 1
)
echo OK - Vistas limpiadas

REM Limpiar cache de rutas
echo [2/6] Limpiando cache de rutas...
php artisan route:clear
echo OK - Rutas limpiadas

REM Limpiar cache de configuracion
echo [3/6] Limpiando cache de configuracion...
php artisan config:clear
echo OK - Configuracion limpiada

REM Limpiar cache de aplicacion
echo [4/6] Limpiando cache de aplicacion...
php artisan cache:clear
echo OK - Cache de aplicacion limpiada

REM Recargar autoloader
echo [5/6] Recargando autoloader de Composer...
composer dump-autoload
echo OK - Autoloader recargado

REM Limpiar vistas compiladas de storage
echo [6/6] Eliminando archivos de vistas compiladas manualmente...
del /Q storage\framework\views\*.php 2>nul
echo OK - Archivos de vistas eliminados

echo.
echo ========================================
echo LIMPIEZA COMPLETADA EXITOSAMENTE!
echo ========================================
echo.
echo Por favor, recarga tu navegador y vuelve a intentar.
echo.
pause
