<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Login as instructor
$user = App\Models\User::find(7);
Auth::login($user);

// Create a proper request through the full stack
$request = Illuminate\Http\Request::create('/instructor/manage-students', 'GET');
$response = $kernel->handle($request);

$content = $response->getContent();

// Look for key indicators in the HTML
$hasStudentTable = strpos($content, 'Student Management Table') !== false;
echo "Has Student Management Table: " . ($hasStudentTable ? 'YES' : 'NO') . "\n";

// Check for view-student buttons (one per student row)
$viewStudentCount = substr_count($content, 'view-student');
echo "View-student buttons: $viewStudentCount\n";

// Check for "No student records found"
$noRecords = strpos($content, 'No student records found');
echo "Shows 'No student records found': " . ($noRecords !== false ? 'YES' : 'NO') . "\n";

// Check total students display
$totalPos = strpos($content, 'Student Total');
$afterTotal = substr($content, $totalPos, 200);
echo "Student total section: $afterTotal\n";

// Extract all student names from the table
preg_match_all('/<div class="font-medium text-gray-900">([^<]+)<\/div>/', $content, $matches);
echo "Student names found: " . implode(', ', $matches[1] ?? []) . "\n";

echo "\ntab-all hidden class check:\n";
if (preg_match('/id="tab-all"\s+class="([^"]*)"/', $content, $m)) {
    echo "  class: {$m[1]}\n";
    echo "  is hidden: " . (strpos($m[1], 'hidden') !== false ? 'YES' : 'NO') . "\n";
}

echo "\n--- First 500 chars of body ---\n";
$bodyStart = strpos($content, '<body');
$bodyContent = substr($content, $bodyStart, 500);
echo $bodyContent . "\n";
