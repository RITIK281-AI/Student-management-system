<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export students to CSV
     */
    public function exportStudentsCSV(Request $request)
    {
        $query = Student::with('course');

        // Apply filters
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $students = $query->get();

        $filename = 'students_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($students) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['ID', 'Name', 'Email', 'Course', 'Marks', 'Grade', 'Status']);

            // Data rows
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->email,
                    $student->course->course_name,
                    $student->marks,
                    $student->grade,
                    $student->marks >= 40 ? 'Pass' : 'Fail',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export students to PDF
     */
    public function exportStudentsPDF(Request $request)
    {
        $query = Student::with('course');

        // Apply filters
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $students = $query->get();

        // Generate HTML for PDF
        $html = view('exports.students-pdf', compact('students'))->render();

        // For now, return as downloadable HTML
        // In production, use a PDF library like TCPDF or DomPDF
        $filename = 'students_' . date('Y-m-d_H-i-s') . '.html';
        
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    /**
     * Export courses to CSV
     */
    public function exportCoursesCSV()
    {
        $courses = Course::withCount('students')->get();

        $filename = 'courses_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($courses) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, ['ID', 'Course Name', 'Duration (hours)', 'Students Count']);

            // Data rows
            foreach ($courses as $course) {
                fputcsv($file, [
                    $course->id,
                    $course->course_name,
                    $course->duration,
                    $course->students_count,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
