<?php

function loadJson($filename) {
    return json_decode(file_get_contents(__DIR__ . '/data/' . $filename), true);
}

function init($studentId, $reportType){

    // Load JSON data
    $students = loadJson('students.json'); // Load student data from JSON file into an array
    $assessments = loadJson('assessments.json'); // Load assessment data from JSON file into an array
    $questions = loadJson('questions.json'); // Load question data from JSON file into an array
    $responses = loadJson('student-responses.json'); // Load student responses data from JSON file into an array

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

}

// Find a student by their ID from the list of students
function findStudent($id, $students) {
    foreach ($students as $s) {
        if ($s['id'] === $id) return $s;  // Return student if ID matches
    }
    return null;  // Return null if no matching student found
}

// Format a date string from 'd/m/Y H:i:s' to a more readable format like '16th December 2021 10:46 AM'
function formatDate($dateString) {
    $dt = DateTime::createFromFormat('d/m/Y H:i:s', $dateString);
    return $dt ? $dt->format('jS F Y h:i A') : "Unknown Date";  // Return formatted date or "Unknown Date" if invalid
}

// Find a question by its ID from the list of questions
function getQuestionById($id, $questions) {
    foreach ($questions as $q) {
        if ($q['id'] === $id) return $q;  // Return question if ID matches
    }
    return null;  // Return null if no matching question found
}

function generateReport($reportType, $studentResponses, $questions, $fullName){

// Sort the student responses array by 'completed' date in ascending order (oldest to newest)
    usort($studentResponses, function ($a, $b) {
        // Convert 'completed' string date into DateTime objects for comparison
        $da = DateTime::createFromFormat('d/m/Y H:i:s', $a['completed']);
        $db = DateTime::createFromFormat('d/m/Y H:i:s', $b['completed']);

        // If both dates are equal, return 0 (no change in order)
        if ($da == $db) return 0;

        // Return -1 if $a is earlier than $b, else return 1
        return ($da < $db) ? -1 : 1;
    });

// Get the most recent (last) assessment response after sorting by date
    $latest = end($studentResponses);

// Get the earliest (first) assessment response after sorting by date
    $first = reset($studentResponses);

    switch ($reportType) {
        case "1": // Diagnostic Report

            // Get the formatted date of the most recent assessment
            $date = formatDate($latest['completed']);

            // Get total number of questions and the raw score (number of correct answers)
            $totalQuestions = count($latest['responses']);
            $rawScore = $latest['results']['rawScore'];

            // Print summary line
            echo "\n";
            echo "$fullName recently completed Numeracy assessment on $date\n";
            echo "He got $rawScore questions right out of $totalQuestions. Details by strand given below:\n\n";

            // Initialize stats array for each strand
            $strandStats = [];

            // Loop through each response in the latest assessment
            foreach ($latest['responses'] as $resp) {
                $q = getQuestionById($resp['questionId'], $questions); // Fetch the question details
                if (!$q) continue;

                $strand = $q['strand']; // Identify the strand

                // Initialize strand entry if not already set
                if (!isset($strandStats[$strand])) {
                    $strandStats[$strand] = ['correct' => 0, 'total' => 0];
                }

                $strandStats[$strand]['total']++; // Increment total questions for the strand

                // Check if the student's answer is correct
                if ($q['config']['key'] === $resp['response']) {
                    $strandStats[$strand]['correct']++; // Increment correct count for the strand
                }
            }

            // Output performance for each strand
            foreach ($strandStats as $strand => $stat) {
                echo "$strand: {$stat['correct']} out of {$stat['total']} correct\n";
            }
            break;

        case "2": // Progress Report

            // Print total assessments taken
            echo "\n";
            echo "$fullName has completed Numeracy assessment " . count($studentResponses) . " times in total. Date and raw score given below:\n\n";

            // Loop through each past assessment to show date and score
            foreach ($studentResponses as $resp) {
                $date = formatDate($resp['assigned']);
                $score = $resp['results']['rawScore'];
                $total = count($resp['responses']);

                echo "Date: $date, Raw Score: $score out of $total\n";
            }

            // Calculate improvement from first to most recent assessment
            $improvement = $latest['results']['rawScore'] - $first['results']['rawScore'];
            echo "\n$fullName got $improvement more correct in the recent completed assessment than the oldest\n";
            break;

        case "3": // Feedback Report

            // Get assessment date and scores
            $date = formatDate($latest['completed']);
            $totalQuestions = count($latest['responses']);
            $rawScore = $latest['results']['rawScore'];

            // Output the general assessment result
            echo "\n";
            echo "$fullName recently completed Numeracy assessment on $date\n";
            echo "He got $rawScore questions right out of $totalQuestions. Feedback for wrong answers given below\n\n";

            // Loop through each response to find incorrect answers
            foreach ($latest['responses'] as $resp) {
                $q = getQuestionById($resp['questionId'], $questions);
                if (!$q) continue;

                $correct = $q['config']['key'];

                // If answer is wrong, show feedback
                if ($resp['response'] !== $correct) {
                    // Find user's selected option and correct answer details
                    $userOption = array_filter($q['config']['options'], fn($o) => $o['id'] === $resp['response']);
                    $correctOption = array_filter($q['config']['options'], fn($o) => $o['id'] === $correct);

                    $user = reset($userOption);
                    $correctAns = reset($correctOption);

                    // Output question, user's answer, correct answer, and hint
                    echo "Question: {$q['stem']}\n";
                    echo "Your answer: {$user['label']} with value {$user['value']}\n";
                    echo "Right answer: {$correctAns['label']} with value {$correctAns['value']}\n";
                    echo "Hint: {$q['config']['hint']}\n";
                }
            }
            break;

        default:
            // Handle invalid report type input
            echo "\n";
            echo "Invalid report type selected.\n";
            break;
    }
}