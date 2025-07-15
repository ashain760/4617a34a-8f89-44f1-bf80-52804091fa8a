<?php

require "functions.php";

// Load JSON data
$students = loadJson('students.json'); // Load student data from JSON file into an array
$assessments = loadJson('assessments.json'); // Load assessment data from JSON file into an array
$questions = loadJson('questions.json'); // Load question data from JSON file into an array
$responses = loadJson('student-responses.json'); // Load student responses data from JSON file into an array

// Get CLI Input
echo "Please enter the following\n";
echo "Student ID: ";
$studentId = trim(fgets(STDIN));

echo "Report to generate (1 for Diagnostic, 2 for Progress, 3 for Feedback): ";
$reportType = trim(fgets(STDIN));

echo "=============================================================================\n";

// Find the student by ID
$student = findStudent($studentId, $students);

if (!$student) {
    echo "\n";
    echo "Student not found.\n";
    exit;
}

$fullName = $student['firstName'] . ' ' . $student['lastName'];

// Filter responses for this student and sort by completion date
$studentResponses = array_filter($responses, function ($resp) use ($studentId) {
    return isset($resp['student']['id']) && $resp['student']['id'] === $studentId && isset($resp['completed']);
});

if (empty($studentResponses)) {
    echo "\n";
    echo "No completed assessments for this student.\n";
    exit;
}

generateReport($reportType, $studentResponses, $questions, $fullName);
