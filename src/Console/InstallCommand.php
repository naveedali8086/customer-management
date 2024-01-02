<?php

namespace Naveedali8086\CustomerManagement\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'customer-mgmt:install';

    protected $description = 'Install the resources for customer management';

    public function handle()
    {
        $this->info("command was run");
    }
}