<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Municipality;
use App\Models\Identification_Type;

class CheckDataCommand extends Command
{
    protected $signature = 'check:data';
    protected $description = 'Check seeded data in municipalities and identification_types tables';

    public function handle()
    {
        $this->info('Checking Municipalities:');
        $municipalities = Municipality::all();
        foreach ($municipalities as $municipality) {
            $this->line("ID: {$municipality->id}, Name: {$municipality->name}");
        }

        $this->info("\nChecking Identification Types:");
        $types = Identification_Type::all();
        foreach ($types as $type) {
            $this->line("ID: {$type->id}, Description: {$type->description}");
        }
    }
}
