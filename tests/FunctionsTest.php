<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../functions.php';

final class FunctionsTest extends TestCase
{
    public function testDiagnosticReport()
    {
        echo "\nTEST RESULT 1 ==================\n";

        $studentId = "student1";
        $reportType = 1;

        ob_start();
        init($studentId, $reportType);
        echo $output = ob_get_clean();

        $this->assertStringContainsString('recently completed Numeracy assessment', $output);
        $this->assertStringContainsString('correct', $output);
    }

    public function testProgressReport()
    {
        echo "\nTEST RESULT 2 ==================\n";

        $studentId = "student1";
        $reportType = 2;

        ob_start();
        init($studentId, $reportType);
        echo $output = ob_get_clean();

        $this->assertStringContainsString('has completed Numeracy assessment', $output);
        $this->assertStringContainsString('Raw Score', $output);
    }

    public function testFeedbackReport()
    {
        echo "\nTEST RESULT 3 ==================\n";

        $studentId = "student1";
        $reportType = 3;

        ob_start();
        init($studentId, $reportType);
        echo $output = ob_get_clean();

        $this->assertStringContainsString('Feedback for wrong answers', $output);
        $this->assertStringContainsString('Hint:', $output);
    }

    public function testStudentNotFound()
    {
        echo "\nTEST RESULT 4 ==================\n";

        $studentId = "student400"; // Invalid ID
        $reportType = 2;

        ob_start();
        init($studentId, $reportType);
        $output = ob_get_clean();

        $this->assertStringContainsString('Student not found', $output);
    }

    public function testInvalidReportType()
    {
        echo "\nTEST RESULT 5 ==================\n";

        $studentId = "student1";
        $reportType = 99; // Invalid

        ob_start();
        init($studentId, $reportType);
        $output = ob_get_clean();

        $this->assertStringContainsString('Invalid report type', $output);
    }
}