<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Str;
use File;

class ModuleRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-repository 
    {repository : The name of the repository}
    {module : The name of the module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run l5-repository make:entity command for a module.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->argument('module');
        $repository = $this->argument('repository');

        config(['repository.generator.basePath' => module_path($module)]);
        config(['repository.generator.rootNamespace' => config('modules.namespace') . '\\' . $module . '\\']);
        config(['repository.generator.stubsOverridePath' => module_path($module)]);

        // $this->call('make:presenter', [
        //     'name' => $repository
        // ]);

        // $this->call('make:validator', [
        //     'name' => $repository
        // ]);

        // Create Controller and Request
        $controller_command = ((float) app()->version() >= 5.5 ? 'make:rest-controller' : 'make:resource');
        $this->call($controller_command, [
            'name' => $repository
        ]);

        // Create Repositories va Entities
        $this->call('make:repository', [
            'name' => $repository,
            '--skip-migration' => true
        ]);

        $this->moveFile();

        // Binding to Provider
        config(['repository.generator.basePath' => app()->path()]);
        config(['repository.generator.stubsOverridePath' => app()->path()]);
        $this->call('make:bindings', [
            'name' => $repository . '/' . $repository
        ]);

        $this->info("{$repository} created in {$module}");
    }

    private function moveFile()
    {
        $module = $this->argument('module');
        $repository = $this->argument('repository');

        $filePaths = [
            'Http/Controllers/' . Str::plural($repository) . 'Controller.php' => 'Http/Controllers/' . $repository . 'Controller.php',
            'Http/Requests/' . $repository . 'CreateRequest.php' => 'Http/Requests/' . $repository . '/CreateRequest.php',
            'Http/Requests/' . $repository . 'UpdateRequest.php' => 'Http/Requests/' . $repository . '/UpdateRequest.php',
            'Repositories/' . $repository . 'Repository.php' => 'Repositories/' . $repository . '/' . $repository . 'Repository.php',
            'Repositories/' . $repository . 'RepositoryEloquent.php' => 'Repositories/' . $repository . '/' . $repository . 'RepositoryEloquent.php'
        ];

        // Create Direcroty
        File::makeDirectory(module_path($module) . '/Http/Requests/' . $repository);
        File::makeDirectory(module_path($module) . '/Repositories//' . $repository);

        // Move file
        foreach ($filePaths as $filePath => $newFilePath) {
            if (File::exists(app_path($filePath))) {
                File::move(app_path($filePath), module_path($module) . '/' . $newFilePath);
            } else {
                File::move(module_path($module) . '/' . $filePath, module_path($module) . '/' . $newFilePath);
            }
            $this->changeContent($module, $newFilePath, $repository);
        }
    }

    private function changeContent($module, $filePath, $repository)
    {
        $fileContent = file_get_contents(module_path($module) . '/' . $filePath);

        // Controller
        $fileContent = str_replace('use App\Http\Requests;', 'use Illuminate\Routing\Controller;', $fileContent);
        $fileContent = str_replace(Str::plural($repository), $repository, $fileContent);

        // Namspace Repository
        $fileContent = str_replace('Repositories', 'Repositories\\' . $repository, $fileContent);

        // Namespace Request
        $fileContent = str_replace('App\\', config('modules.namespace') . '\\' . $module . '\\', $fileContent);
        $fileContent = str_replace('Requests', 'Requests\\' . $repository, $fileContent);

        // Name Request
        $fileContent = str_replace($repository . 'CreateRequest', 'CreateRequest', $fileContent);
        $fileContent = str_replace($repository . 'UpdateRequest', 'UpdateRequest', $fileContent);

        file_put_contents(module_path($module) . '/' . $filePath, $fileContent);
    }
}
