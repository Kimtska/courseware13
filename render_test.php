<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(7);
Auth::login($user);

$controller = new App\Http\Controllers\InstructorStudentManagementController();
$ref = new ReflectionMethod($controller, 'index');
$request = Illuminate\Http\Request::create('/instructor/manage-students', 'GET');
$response = $ref->invoke($controller, $request);

$html = $response->render();

// Extract the table section
$start = strpos($html, 'Student Management Table');
$end = strpos($html, '5 Months Old', $start);
echo substr($html, $start, $end - $start) . "\n\n";

// Check for student rows
$studentCount = substr_count($html, 'view-student');
echo "\nView-student buttons found: $studentCount\n";

$noRecordsFound = strpos($html, 'No student records found');
echo "Contains 'No student records found': " . ($noRecordsFound !== false ? 'YES' : 'NO') . "\n";

echo "\ntab-all hidden? " . (strpos($html, 'id="tab-all" class="tab-content hidden"') !== false ? 'YES' : 'NO') . "\n";
echo "tab-all no hidden? " . (strpos($html, 'id="tab-all" class="tab-content"') !== false ? 'YES' : 'NO') . "\n";
