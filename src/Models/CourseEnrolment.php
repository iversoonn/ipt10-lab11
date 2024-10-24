<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class CourseEnrolment extends BaseModel
{
    public function enroll($course_code, $student_code, $enrolment_date)
    {
        $sql = "INSERT INTO course_enrolments SET 
                    course_code = :course_code,
                    student_code = :student_code,
                    enrolment_date = :enrolment_date";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'course_code' => $course_code,
            'student_code' => $student_code,
            'enrolment_date' => $enrolment_date
        ]);
    }
}
