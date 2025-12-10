<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class GenerateDataSeeder extends Command
{
    protected $signature = 'make:data-seeder';

    protected $description = 'Genera un seeder con todos los datos actuales de la base de datos';

    protected $tables = [
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

    public function handle()
    {
        $this->info('Generando seeder con datos actuales...');

        $allData = [];

        foreach ($this->tables as $table) {
            $data = DB::table($table)->get();

            if ($data->isNotEmpty()) {
                $allData[$table] = $data->toArray();
                $this->info("✓ Tabla {$table}: {$data->count()} registros");
            } else {
                $this->warn("⚠ Tabla {$table}: vacía");
            }
        }

        $seederContent = $this->generateSeederFile($allData);

        $path = database_path('seeders/SnapshotDataSeeder.php');
        File::put($path, $seederContent);

        $this->info("\n✓ Seeder generado: database/seeders/SnapshotDataSeeder.php");
        $this->info("\nPara usar este seeder:");
        $this->info('1. Agrégalo a DatabaseSeeder.php');
        $this->info('2. Ejecuta: php artisan db:seed --class=SnapshotDataSeeder');

        return 0;
    }

    protected function generateSeederFile($allData)
    {
        $insertStatements = '';

        foreach ($allData as $table => $records) {
            $recordsArray = json_decode(json_encode($records), true);
            $formattedData = $this->formatArrayForPHP($recordsArray, 2);

            $insertStatements .= "\n        // Tabla: {$table}\n";
            $insertStatements .= "        DB::table('{$table}')->insert({$formattedData});\n";
        }

        return <<<PHP
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SnapshotDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
{$insertStatements}
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
PHP;
    }

    protected function formatArrayForPHP($array, $indentLevel = 0)
    {
        $indent = str_repeat('    ', $indentLevel);
        $output = "[\n";

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $output .= $indent.'    '.var_export($key, true).' => '.$this->formatArrayForPHP($value, $indentLevel + 1).",\n";
            } else {
                $output .= $indent.'    '.var_export($key, true).' => '.var_export($value, true).",\n";
            }
        }

        $output .= $indent.']';

        return $output;
    }
}
