<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
Illuminate\Support\Facades\Mail::setFacadeApplication($app);
try {
    $mailable = new App\Mail\UserOtpEmail('123456');
    Illuminate\Support\Facades\Mail::to('junioraniseth@gmail.com')->send($mailable);
    echo "MAIL_SENT\n";
    var_export(Illuminate\Support\Facades\Mail::failures());
} catch (Throwable $e) {
    echo get_class($e) . ': ' . $e->getMessage() . "\n";
}
