<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserRole;

class CheckUsersCommand extends Command
{
    protected $signature = 'check:users';
    protected $description = 'Check registered users and their roles';

    public function handle()
    {
        $this->info('Checking Users:');
        $users = User::all();
        foreach ($users as $user) {
            $this->line("\nUser ID: {$user->id}");
            $this->line("Email: {$user->email}");
            $this->line("Name: {$user->name}");
            
            $role = UserRole::where('user_id', $user->id)->first();
            if ($role) {
                $this->line("User Role ID: {$role->id}");
                $this->line("Identification: {$role->identification}");
                $this->line("Full Name: {$role->name} {$role->last_name}");
            } else {
                $this->error("No role found for this user");
            }
        }
    }
}
