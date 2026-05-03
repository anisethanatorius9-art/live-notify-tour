<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'like', '%akibonyeza%')->first();
if ($user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Role: " . ($user->role ?? 'NULL') . "\n";
    echo "Role selected at: " . ($user->role_selected_at ?? 'NULL') . "\n";
} else {
    echo "User not found\n";
}
