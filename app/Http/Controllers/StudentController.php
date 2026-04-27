<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students with search and filter
     */
    public function index(Request $request)
    {
        $query = Student::with('course');

        // Search by student name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Paginate results (10 per page)
        $students = $query->paginate(10);
        $courses = Course::all();

        return view('students.index', compact('students', 'courses'));
    }

    /**
     * Show the form for creating a new student (Staff only)
     */
    public function create()
    {
        $courses = Course::all();
        return view('students.create', compact('courses'));
    }

    /**
     * Store a newly created student in database (Staff only)
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'course_id' => 'required|exists:courses,id',
            'marks' => 'required|integer|min:0|max:100',
        ]);

        // Create student
        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully!');
    }

    /**
     * Show the form for editing a student (Staff only)
     */
    public function edit(Student $student)
    {
        $courses = Course::all();
        return view('students.edit', compact('student', 'courses'));
    }

    /**
     * Update the specified student in database (Staff only)
     */
    public function update(Request $request, Student $student)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'course_id' => 'required|exists:courses,id',
            'marks' => 'required|integer|min:0|max:100',
        ]);

        // Update student
        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Delete the specified student (Staff only)
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Show staff dashboard
     */
    public function staffDashboard()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $recentStudents = Student::with('course')->latest()->take(5)->get();

        return view('dashboards.staff', compact('totalStudents', 'totalCourses', 'recentStudents'));
    }

    /**
     * Show admin dashboard
     */
    public function adminDashboard()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $students = Student::with('course')->paginate(10);

        return view('dashboards.admin', compact('totalStudents', 'totalCourses', 'students'));
    }

    /**
     * Show CEO dashboard
     */
    public function ceoDashboard()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $students = Student::with('course')->paginate(10);

        return view('dashboards.ceo', compact('totalStudents', 'totalCourses', 'students'));
    }
}
