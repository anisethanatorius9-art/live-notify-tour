<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
Illuminate\Support\Facades\Facade::setFacadeApplication($app);
echo "mail.default=" . config('mail.default') . "\n";
echo "smtp.host=" . config('mail.mailers.smtp.host') . "\n";
echo "smtp.transport=" . config('mail.mailers.smtp.transport') . "\n";
echo "smtp.encryption=" . config('mail.mailers.smtp.encryption') . "\n";
echo "from.address=" . config('mail.from.address') . "\n";
echo "env MAIL_MAILER=" . env('MAIL_MAILER') . "\n";
echo "env MAIL_HOST=" . env('MAIL_HOST') . "\n";
echo "env MAIL_PORT=" . env('MAIL_PORT') . "\n";
echo "env MAIL_ENCRYPTION=" . env('MAIL_ENCRYPTION') . "\n";
