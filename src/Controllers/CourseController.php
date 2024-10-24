<?php

namespace App\Controllers;

use App\Models\Course;
use App\Controllers\BaseController;
use Fpdf\Fpdf;

class CourseController extends BaseController
{
    public function list()
    {
        $courseModel = new Course();
        $courses = $courseModel->all();

        $template = 'courses';
        $data = [
            'items' => $courses
        ];

        return $this->render($template, $data);
    }

    public function viewCourse($course_code)
    {
        $courseModel = new Course();
        $course = $courseModel->find($course_code);
        $enrollees = $courseModel->getEnrolees($course_code);

        $template = 'single-course';
        $data = [
            'course' => $course,
            'enrollees' => $enrollees
        ];

        return $this->render($template, $data);
    }

    public function exportPDF($course_code) {
        $obj = new Course();
        
        // Get course details
        $course = $obj->find($course_code);  
        // Get enrollees for the course
        $enrollees = $obj->getEnrolees($course_code);
    
        // Create a new PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Set title
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->SetTextColor(0, 51, 102); // Dark blue color
        $pdf->Cell(0, 10, 'Course Information', 0, 1, 'C');
        
        // Add a line separator
        $pdf->SetDrawColor(0, 51, 102);
        $pdf->Line(10, 20, 200, 20);
        
        // Line break
        $pdf->Ln(10);
        
        // Course details
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->Cell(50, 10, 'Course Code:', 0, 0, 'L');
        $pdf->Cell(0, 10, $course->course_code, 0, 1, 'L');
        
        $pdf->Cell(50, 10, 'Course Name:', 0, 0, 'L');
        $pdf->Cell(0, 10, $course->course_name, 0, 1, 'L');
    
        $pdf->Cell(50, 10, 'Description:', 0, 0, 'L');
        $pdf->MultiCell(0, 10, $course->description, 0, 'L');
        
        $pdf->Cell(50, 10, 'Credits:', 0, 0, 'L');
        $pdf->Cell(0, 10, $course->credits, 0, 1, 'L');
        
        // Line break
        $pdf->Ln(10);
        
        // List of enrollees title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor(0, 51, 102);
        $pdf->Cell(0, 10, 'List of Enrollees', 0, 1, 'C');  
        
        // Line break
        $pdf->Ln(5);
        
        // Define column widths
        $columnWidths = [
            'ID' => 20,
            'First Name' => 40,
            'Last Name' => 40,
            'Email' => 60,
            'Date of Birth' => 30,
            'Sex' => 20,
        ];
        
        // Set header for enrollees table
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(100, 149, 237); // Fill color for header
        $pdf->SetTextColor(255, 255, 255); // Text color for header
    
        // Create the header row
        $pdf->SetX(($pdf->GetPageWidth() - array_sum($columnWidths)) / 2);
        foreach ($columnWidths as $colName => $width) {
            $pdf->Cell($width, 10, $colName, 1, 0, 'C', true);
        }
        $pdf->Ln();
        
        // Reset font and colors for table content
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0); 
    
        if (!empty($enrollees)) {
            foreach ($enrollees as $enrollee) {
                // Set X position for content cells
                $pdf->SetX(($pdf->GetPageWidth() - array_sum($columnWidths)) / 2);
                foreach ($columnWidths as $width) {
                    $pdf->Cell($width, 10, '', 1); // Temporary empty cell
                }
                // Output the data in the next row
                $pdf->SetX(($pdf->GetPageWidth() - array_sum($columnWidths)) / 2);
                $pdf->Cell($columnWidths['ID'], 10, $enrollee["student_code"], 1);
                $pdf->Cell($columnWidths['First Name'], 10, $enrollee["first_name"], 1);
                $pdf->Cell($columnWidths['Last Name'], 10, $enrollee["last_name"], 1);
                $pdf->Cell($columnWidths['Email'], 10, $enrollee["email"], 1);
                $pdf->Cell($columnWidths['Date of Birth'], 10, $enrollee["date_of_birth"], 1);
                $pdf->Cell($columnWidths['Sex'], 10, $enrollee["sex"], 1);
                $pdf->Ln();
            }
        } else {
            // If no enrollees, display a message
            $pdf->SetX(($pdf->GetPageWidth() - array_sum($columnWidths)) / 2);
            $pdf->Cell(array_sum($columnWidths), 10, 'No enrollees found for this course.', 1, 1, 'C');
        }
        
        // Output the PDF
        $pdf->Output('D', 'course_' . $course_code . '_enrollees.pdf');
    }
}
