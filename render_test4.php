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

$data = $response->getData();

// Add $errors variable
$data['errors'] = new Illuminate\Support\MessageBag();

$html = view('Instructor.manage-students', $data)->render();

// Write to file for inspection
file_put_contents(__DIR__ . '/render_output.html', $html);
echo "HTML written to render_output.html (" . strlen($html) . " bytes)\n";

// Check key indicators
echo "Student names found:\n";
$pattern = '/<div class="font-medium text-gray-900">(.*?)<\/div>/s';
preg_match_all($pattern, $html, $matches);
if (!empty($matches[1])) {
    foreach ($matches[1] as $name) {
        echo "  - $name\n";
    }
} else {
    echo "  NONE FOUND\n";
}

$hasTable = strpos($html, 'Student Management Table') !== false;
echo "Has table header: " . ($hasTable ? 'YES' : 'NO') . "\n";

$viewBtns = substr_count($html, 'view-student');
echo "View buttons: $viewBtns\n";

$emptyMsg = strpos($html, 'No student records found');
echo "Empty message: " . ($emptyMsg !== false ? 'PRESENT' : 'NOT PRESENT') . "\n";

// Check tab-all class
preg_match('/id="tab-all"\s+class="([^"]*)"/', $html, $m);
echo "tab-all class: " . ($m[1] ?? 'NOT FOUND') . "\n";
