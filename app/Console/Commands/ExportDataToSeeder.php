<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ExportDataToSeeder extends Command
{
    protected $signature = 'db:export-seeders';

    protected $description = 'Exporta los datos actuales de la base de datos a seeders';

    public function handle()
    {
        $this->info('Exportando datos a seeders...');

        $tables = [
            'roles',
            'carreras',
            'perfiles',
            'categorias',
            'eventos',
            'criterio_evaluacion',
            'users',
            'user_rol',
            'participantes',
            'evento_participante',
            'constancias',
            'equipos',
            'equipo_participante',
            'proyectos',
            'avances',
            'calificaciones',
            'proyecto_juez',
        ];

        foreach ($tables as $table) {
            $this->exportTable($table);
        }

        $this->info('¡Exportación completada!');

        return 0;
    }

    protected function exportTable($table)
    {
        $data = DB::table($table)->get();

        if ($data->isEmpty()) {
            $this->warn("Tabla {$table} está vacía, omitiendo...");

            return;
        }

        $className = str_replace('_', '', ucwords($table, '_')).'DataSeeder';
        $seederPath = database_path("seeders/{$className}.php");

        $content = $this->generateSeederContent($className, $table, $data);

        File::put($seederPath, $content);

        $this->info("✓ Seeder creado: {$className}");
    }

    protected function generateSeederContent($className, $table, $data)
    {
        $dataArray = $data->toArray();
        $dataString = var_export(json_decode(json_encode($dataArray), true), true);

        return <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class {$className} extends Seeder
{
    public function run(): void
    {
        DB::table('{$table}')->insert({$dataString});
    }
}
PHP;
    }
}
