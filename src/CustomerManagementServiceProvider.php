<?php

namespace Naveedali8086\CustomerManagement;

use Illuminate\Support\ServiceProvider;
use Naveedali8086\CustomerManagement\Console\InstallCommand;

class CustomerManagementServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            InstallCommand::class
        ]);
    }

    public function provides()
    {
        return [InstallCommand::class];
    }
}