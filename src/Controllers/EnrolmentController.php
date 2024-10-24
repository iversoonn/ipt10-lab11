<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\CourseEnrolment;
use App\Models\Student;
use App\Controllers\BaseController;

class EnrolmentController extends BaseController
{
    public function enrollmentForm()
    {
        $courseObj = new Course();
        $studentObj = new Student();

        $template = 'enrollment-form';
        $data = [
            'courses' => $courseObj->all(),
            'students' => $studentObj->all()
        ];
        $output = $this->render($template, $data);
        return $output;
    }

    public function enroll()
    {
        // Correctly match POST variable names
        $course_code = $_POST['course_code'];
        $student_code = $_POST['student_code'];
        $enrollment_date = $_POST['enrollment_date']; // Fixed variable name

        $enrolmentModel = new CourseEnrolment();
       
        $enrolmentModel->enroll($course_code, $student_code, $enrollment_date);

        // Redirect to the course page after enrollment
         header("Location: /courses/{$course_code}");
         exit();
    }
}
