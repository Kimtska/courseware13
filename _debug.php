<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Auth;

$r1 = new ReflectionMethod(get_class(Auth::guard('student')), 'getCookieName');
$r1->setAccessible(true);
echo "Student guard cookie name: " . $r1->invoke(Auth::guard('student')) . "\n";

$r2 = new ReflectionMethod(get_class(Auth::guard('web')), 'getCookieName');
$r2->setAccessible(true);
echo "Web guard cookie name: " . $r2->invoke(Auth::guard('web')) . "\n";

$r3 = new ReflectionMethod(get_class(Auth::guard('student')), 'getName');
$r3->setAccessible(true);
echo "Student session name: " . $r3->invoke(Auth::guard('student')) . "\n";

$r4 = new ReflectionMethod(get_class(Auth::guard('web')), 'getName');
$r4->setAccessible(true);
echo "Web session name: " . $r4->invoke(Auth::guard('web')) . "\n";

$r5 = new ReflectionMethod(get_class(Auth::guard('student')), 'getRecallerName');
$r5->setAccessible(true);
echo "Student recaller name: " . $r5->invoke(Auth::guard('student')) . "\n";

$r6 = new ReflectionMethod(get_class(Auth::guard('web')), 'getRecallerName');
$r6->setAccessible(true);
echo "Web recaller name: " . $r6->invoke(Auth::guard('web')) . "\n";

unlink(__FILE__);
