<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Course extends BaseModel
{
    public function all()
    {
        // Query to fetch all courses along with the count of enrolled students
        $sql = "
            SELECT c.*, 
            (SELECT COUNT(*) FROM course_enrolments WHERE course_code = c.course_code) AS enrolee_count 
            FROM courses AS c";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_CLASS, '\App\Models\Course');
        return $result;
    }

    public function find($code)
    {
        $sql = "SELECT * FROM courses WHERE course_code = ?";
        $statement = $this->db->prepare($sql);
        $statement->execute([$code]);
        $result = $statement->fetchObject('\App\Models\Course');
        return $result;
    }

    public function getEnrolees($course_code)
    {
       $sql = "SELECT s.student_code, CONCAT(s.first_name, ' ', s.last_name ) AS student_name, s.first_name, s.last_name, s.email, s.date_of_birth, s.sex
                FROM course_enrolments AS ce
                LEFT JOIN students AS s ON (s.student_code=ce.student_code)
                WHERE ce.course_code = :course_code";
        $statement = $this->db->prepare($sql);
        $statement->execute(['course_code' => $course_code]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC,);
        return $result;
    }

    public function getCourseCode()
    {
        return $this->course_code; // Assuming course_code is a property
    }

    public function getCourseName()
    {
        return $this->course_name; // Assuming course_name is a property
    }
}
