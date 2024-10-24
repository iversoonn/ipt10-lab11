<?php

namespace App\Controllers;

use App\Models\Student;
use App\Controllers\BaseController;

class StudentController extends BaseController
{
    // Method to list all students
    public function list()
    {
        $studentModel = new Student();
        $students = $studentModel->all();

        $template = 'students';
        $data = [
            'students' => $students // Changed 'items' to 'students' for clarity
        ];

        return $this->render($template, $data);
    }

    // Method to view a single student by student code
    public function viewStudent($student_code)
    {
        $studentModel = new Student();
        $student = $studentModel->find($student_code); // Fetch student details by code

        if (!$student) {
            // Handle case where student is not found
            return $this->render('error', ['message' => 'Student not found.']);
        }

        $template = 'single-student';
        $data = [
            'student' => $student
        ];

        return $this->render($template, $data);
    }
}
