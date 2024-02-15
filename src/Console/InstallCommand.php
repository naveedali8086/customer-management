<?php

namespace Naveedali8086\CustomerManagement\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'customer-crud:install';

    protected $description = 'Install the CRUD functionality for customer management';

    public function handle()
    {
        $fileSystem = new Filesystem();

        // Copy controllers
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Http/Controllers', app_path('Http/Controllers'));

        // Copy requests
        $fileSystem->ensureDirectoryExists(app_path('Http/Requests'));
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Http/Requests', app_path('Http/Requests'));

        // Copy models
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Models', app_path('Models'));

        // copy factories
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/database/factories', base_path('database/factories'));

        // copy seeders
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/database/seeders', base_path('database/seeders'));

        // copy migrations
        $files = File::glob(__DIR__ . '/../../stubs/database/migrations/*.php');
        copy_migrations(database_path('migrations'), $files);

        // Copy policies
        $fileSystem->ensureDirectoryExists(app_path('Policies'));
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Policies', app_path('Policies'));

        // Copy resources
        $fileSystem->ensureDirectoryExists(app_path('Http/Resources'));
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/app/Http/Resources', app_path('Http/Resources'));

        // copy tests
        $fileSystem->copyDirectory(__DIR__ . '/../../stubs/tests/Feature', base_path('tests/Feature'));

        $this->info("customer-management scaffolding installed successfully");
    }
}