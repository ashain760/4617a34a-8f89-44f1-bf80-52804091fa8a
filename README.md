# Assessment Reporting CLI Application

## Overview

This PHP CLI application generates different types of assessment reports (Diagnostic, Progress, Feedback) for students based on their assessment responses. The application reads data from JSON files and produces formatted report output in the terminal.

---

## Prerequisites

- PHP 7.0 or higher installed on your system.  
- Command line access (Terminal or Command Prompt).  
- JSON data files (`students.json`, `assessments.json`, `questions.json`, `student-responses.json`) placed in the data directory.  

---

## Installation

1. **Clone the repository** (if applicable) or download the project files to your local machine.

2. Ensure that the JSON data files are present in the root directory alongside the PHP script.

3. Run `composer install`

---

## Running the Application

Open your terminal and navigate to the project directory.

Run the PHP CLI script using the following command:

```bash
php report.php
````

## Test the Application

```bash
composer test
````