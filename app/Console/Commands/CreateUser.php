<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    protected $signature = 'user:create-admin';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->info('Admin user created successfully!');
    }
}