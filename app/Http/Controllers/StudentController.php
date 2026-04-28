<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display list of students with search and filter
     */
    public function index(Request $request)
    {
        $query = Student::with('course');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by course
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by grade
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        // Filter by marks range
        if ($request->filled('marks_min')) {
            $query->where('marks', '>=', $request->marks_min);
        }
        if ($request->filled('marks_max')) {
            $query->where('marks', '<=', $request->marks_max);
        }

        $students = $query->paginate(10);
        $courses = Course::all();

        return view('students.index', compact('students', 'courses'));
    }

    /**
     * Show create student form (Staff only)
     */
    public function create()
    {
        $courses = Course::all();
        return view('students.create', compact('courses'));
    }

    /**
     * Store new student (Staff only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students',
            'course_id' => 'required|exists:courses,id',
            'marks' => 'required|integer|min:0|max:100',
        ]);

        $student = Student::create($validated);

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'created_student',
            "Created student: {$student->name}",
            'Student',
            $student->id
        );

        return redirect()->route('students.index')
            ->with('success', 'Student added successfully!');
    }

    /**
     * Show edit student form (Staff only)
     */
    public function edit(Student $student)
    {
        $courses = Course::all();
        return view('students.edit', compact('student', 'courses'));
    }

    /**
     * Update student (Staff only)
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'course_id' => 'required|exists:courses,id',
            'marks' => 'required|integer|min:0|max:100',
        ]);

        $student->update($validated);

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'updated_student',
            "Updated student: {$student->name}",
            'Student',
            $student->id
        );

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Delete student (Staff only)
     */
    public function destroy(Student $student)
    {
        $studentName = $student->name;
        $student->delete();

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'deleted_student',
            "Deleted student: {$studentName}",
            'Student',
            $student->id
        );

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }

    /**
     * Show student profile page
     */
    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    /**
     * Restore soft-deleted student (Staff only)
     */
    public function restore($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $student->restore();

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'restored_student',
            "Restored student: {$student->name}",
            'Student',
            $student->id
        );

        return redirect()->route('students.index')
            ->with('success', 'Student restored successfully!');
    }

    /**
     * Permanently delete student (Staff only)
     */
    public function forceDelete($id)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $studentName = $student->name;
        $student->forceDelete();

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'permanently_deleted_student',
            "Permanently deleted student: {$studentName}",
            'Student',
            $id
        );

        return redirect()->route('students.index')
            ->with('success', 'Student permanently deleted!');
    }
}
