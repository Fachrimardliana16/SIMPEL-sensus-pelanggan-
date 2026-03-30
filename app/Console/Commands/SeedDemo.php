<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedDemo extends Command
{
    protected $signature = 'surveipro:seed-demo {--surveys=5} {--responses=20}';
    protected $description = 'Seed demo data for SurveiPro';

    public function handle()
    {
        $this->info('Starting SurveiPro demo seeder...');
        $seeder = new \Database\Seeders\DatabaseSeeder();
        $seeder->run((int) $this->option('surveys'), (int) $this->option('responses'));
        $this->info('Demo seeded successfully!');
    }
}
