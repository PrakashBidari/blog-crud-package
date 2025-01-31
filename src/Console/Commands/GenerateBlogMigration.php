<?php

namespace Laraphant\Blogcrud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateBlogMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blogcrud:make-migration {table} {--fields=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a blog migration with specified fields';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $table = $this->argument('table');
        $modelName = Str::singular(ucfirst($table)); // Ensure model is singular
        $modelVariable = Str::camel($modelName); // Convert to camelCase for controller variable
        $fieldsOption = $this->option('fields');

        if (!$fieldsOption) {
            $this->error('The --fields option is required.');
            return 1;
        }

        // Process fields for migration
        $fields = explode(',', $fieldsOption);
        $migrationFields = '';

        foreach ($fields as $field) {
            [$name, $type] = explode(':', $field);
            $migrationFields .= "\$table->$type('$name');\n            ";
        }

        // Load migration stub
        $migrationStubPath = __DIR__ . '/stubs/migration.stub';
        if (!File::exists($migrationStubPath)) {
            $this->error('Migration stub file not found!');
            return 1;
        }

        $migrationStub = File::get($migrationStubPath);
        $migrationContent = str_replace(
            ['{{table}}', '{{fields}}'],
            [$table, $migrationFields],
            $migrationStub
        );

        $timestamp = date('Y_m_d_His');
        $migrationFileName = "{$timestamp}_create_{$table}_table.php";
        $migrationPath = database_path("migrations/{$migrationFileName}");

        File::put($migrationPath, $migrationContent);
        $this->info("Migration created successfully: {$migrationPath}");

        // Extract fields for model
        preg_match_all('/\$table->(\w+)\(\'(\w+)\'/', $migrationContent, $matches);
        if (!isset($matches[2])) {
            $this->error('No fields found in the migration!');
            return 1;
        }

        $fillable = implode("',\n        '", $matches[2]);

        // Generate the model
        $modelStubPath = __DIR__ . '/stubs/model.stub';
        if (!File::exists($modelStubPath)) {
            $this->error('Model stub file not found!');
            return 1;
        }

        $modelStub = file_get_contents($modelStubPath);
        $modelStub = str_replace(
            ['{{model}}', 'protected $fillable = ['],
            [$modelName, "protected \$fillable = [\n        '$fillable',"],
            $modelStub
        );

        // Save the model
        $modelPath = app_path("Models/{$modelName}.php");
        File::put($modelPath, $modelStub);
        $this->info("Model created successfully at: {$modelPath}");

        // Generate the controller
        $controllerStubPath = __DIR__ . '/stubs/controller.stub';
        if (!File::exists($controllerStubPath)) {
            $this->error('Controller stub file not found!');
            return 1;
        }

        $controllerStub = file_get_contents($controllerStubPath);
        $controllerStub = str_replace(
            ['{{model}}', '{{modelVariable}}'],
            [$modelName, $modelVariable],
            $controllerStub
        );

        // Ensure the backend controllers directory exists
        $controllerDir = app_path('Http/Controllers/backend');
        if (!File::exists($controllerDir)) {
            File::makeDirectory($controllerDir, 0755, true);
        }

        // Save the controller
        $controllerPath = "{$controllerDir}/{$modelName}Controller.php";
        File::put($controllerPath, $controllerStub);
        $this->info("Controller created successfully at: {$controllerPath}");

        // Append route
        $routeContent = "\nRoute::resource('$table', {$modelName}Controller::class);\n";
        $routeFile = base_path('routes/web.php');

        if (!File::exists($routeFile)) {
            $this->error('Route file not found!');
            return 1;
        }

        File::append($routeFile, $routeContent);
        $this->info("Route added successfully in routes/web.php.");

        return 0;
    }
}
