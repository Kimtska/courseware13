<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Auth::login(App\Models\User::find(7));

$controller = new App\Http\Controllers\InstructorStudentManagementController();
$ref = new ReflectionMethod($controller, 'index');
$request = Illuminate\Http\Request::create('/instructor/manage-students', 'GET');
$response = $ref->invoke($controller, $request);

// Get view data  
$data = $response->getData();

// Now render just the section content that contains the table
$html = view('Instructor.manage-students', $data)->render();
echo "HTML length: " . strlen($html) . "\n";

$hasTable = strpos($html, 'Student Management Table') !== false;
echo "Table heading present: " . ($hasTable ? 'YES' : 'NO') . "\n";

$viewBtns = substr_count($html, 'view-student');
echo "View-student buttons: " . $viewBtns . "\n";

$emptyMsg = strpos($html, 'No student records found');
echo "Empty message: " . ($emptyMsg !== false ? 'PRESENT' : 'NOT PRESENT') . "\n";

$tabAllHidden = preg_match('/id="tab-all"\s+class="([^"]*)"/', $html, $m);
echo "tab-all classes: " . ($tabAllHidden ? $m[1] : 'NOT FOUND') . "\n";
echo "tab-all has hidden: " . ($tabAllHidden && strpos($m[1], 'hidden') !== false ? 'YES' : 'NO') . "\n";

// Check student names in payloads
preg_match_all('/full_name\\\u0022;\\\u0022([^\\\]+)/', $html, $nameMatches);
if (!empty($nameMatches[1])) {
    echo "Student names in JSON: " . implode(', ', $nameMatches[1]) . "\n";
} else {
    preg_match_all('/"full_name":"([^"]+)"/', $html, $nameMatches2);
    if (!empty($nameMatches2[1])) {
        echo "Student names in JSON: " . implode(', ', $nameMatches2[1]) . "\n";
    }
}

echo "\nTotal students value: " . $data['totalStudents'] . "\n";
echo "Paginator count: " . $data['students']->count() . "\n";
echo "Paginator total: " . $data['students']->total() . "\n";
