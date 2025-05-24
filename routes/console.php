<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('user:create-admin', function () {
    \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => bcrypt('password123'),
    ]);
    $this->info('Admin created!');
});