<?php

namespace App\Models;

use App\Models\BaseModel;
use \PDO;

class Student extends BaseModel
{
    // Properties
    public $student_code;
    public $first_name;
    public $last_name;
    public $email;
    public $date_of_birth; // New property
    public $sex;         // New property

    // Method to fetch all students
    public function all()
    {
        $sql = "SELECT id, student_code, CONCAT(first_name, ' ',  last_name) AS student_name, first_name, last_name, email, date_of_birth, sex FROM students";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_CLASS, '\App\Models\Student');
        return $result;
    }

    // Method to find a student by student code
    public function find($student_code)
    {
        $sql = "SELECT * FROM students WHERE student_code = :student_code";
        $statement = $this->db->prepare($sql);
        $statement->bindParam(':student_code', $student_code);
        $statement->execute();
        return $statement->fetchObject('\App\Models\Student');
    }

    // Getter for student code
    public function getStudentCode()
    {
        return $this->student_code;
    }

    // Getter for first name
    public function getFirstName()
    {
        return $this->first_name;
    }

    // Getter for last name
    public function getLastName()
    {
        return $this->last_name;
    }

    // Getter for email
    public function getEmail()
    {
        return $this->email;
    }

    // Getter for date of birth
    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    // Getter for sex
    public function getSex()
    {
        return $this->sex;
    }

    // Method to get full name
    public function getFullName()
    {
        $sql = "SELECT first_name || last_name AS student_name FROM students";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_CLASS, '\App\Models\Student');
        return $result;
    }
}
