<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../functions.php';

final class FunctionsTest extends TestCase
{
    public function testData1()
    {
        echo "\nTEST RESULT 1 ========================= \n";

        $studentId = "student1";
        $reportType = 1;

        // Start output buffering
        ob_start();
        init($studentId, $reportType);
        $output1 = ob_get_clean(); // Capture all output
        echo $output1;

        // Assert that output contains expected substrings
        $this->assertStringContainsString('correct', $output1);

    }

    public function testData2()
    {
        echo "\nTEST RESULT 2 ========================= \n";

        $studentId = "student1";
        $reportType = 2;

        // Start output buffering
        ob_start();
        init($studentId, $reportType);
        $output2 = ob_get_clean(); // Capture all output
        echo $output2;

        // Assert that output contains expected substrings
        $this->assertStringContainsString('correct', $output2);

    }

    public function testData3()
    {
        echo "\nTEST RESULT 3 ========================= \n";

        $studentId = "student1";
        $reportType = 3;

        // Start output buffering
        ob_start();
        init($studentId, $reportType);
        $output3 = ob_get_clean(); // Capture all output
        echo $output3;

        // Assert that output contains expected substrings
        $this->assertStringContainsString('correct', $output3);

    }

    public function testData4()
    {
        echo "\nTEST RESULT 4 ========================= \n";

        $studentId = "student400";
        $reportType = 2;

        // Start output buffering
        ob_start();
        init($studentId, $reportType);
        $output4 = ob_get_clean(); // Capture all output
        echo $output4;

        // Assert that output contains expected substrings
        $this->assertStringContainsString('correct', $output4);

    }

    public function testData5()
    {
        echo "\nTEST RESULT 5 ========================= \n";

        $studentId = "student1";
        $reportType = 10;

        // Start output buffering
        ob_start();
        init($studentId, $reportType);
        $output5 = ob_get_clean(); // Capture all output
        echo $output5;

        // Assert that output contains expected substrings
        $this->assertStringContainsString('correct', $output5);

    }

}