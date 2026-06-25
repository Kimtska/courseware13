<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;

foreach (['student', 'web'] as $g) {
    $guard = Auth::guard($g);
    
    $rp = new ReflectionProperty(get_class($guard), 'recallerName');
    $rp->setAccessible(true);
    echo "$g recallerName: " . $rp->getValue($guard) . "\n";
    
    $rp2 = new ReflectionProperty(get_class($guard), 'name');
    $rp2->setAccessible(true);
    echo "$g session name: " . $rp2->getValue($guard) . "\n";
}

unlink(__FILE__);
