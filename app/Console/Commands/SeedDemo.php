<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedDemo extends Command
{
    protected $signature = 'simpel:seed-demo {--surveys=5} {--responses=20}';
    protected $description = 'Seed demo data for SIMPEL';

    public function handle()
    {
        $this->info('Starting SIMPEL demo seeder...');
        $seeder = new \Database\Seeders\DatabaseSeeder();
        $seeder->run((int) $this->option('surveys'), (int) $this->option('responses'));
        $this->info('Demo seeded successfully!');
    }
}
