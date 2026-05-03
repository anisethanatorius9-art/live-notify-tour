<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::all();
foreach ($users as $user) {
    echo "ID: {$user->id} | Name: {$user->name} | Role: " . ($user->role ?? 'NULL') . " | Role sel: " . ($user->role_selected_at ?? 'NULL') . "\n";
}
