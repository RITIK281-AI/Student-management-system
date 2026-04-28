<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display list of courses
     */
    public function index()
    {
        $courses = Course::withCount('students')->get();
        return view('courses.index', compact('courses'));
    }

    /**
     * Show create course form (Staff only)
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store new course (Staff only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255|unique:courses',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
        ]);

        $course = Course::create($validated);

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'created_course',
            "Created course: {$course->course_name}",
            'Course',
            $course->id
        );

        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully!');
    }

    /**
     * Show edit course form (Staff only)
     */
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }

    /**
     * Update course (Staff only)
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_name' => 'required|string|max:255|unique:courses,course_name,' . $course->id,
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
        ]);

        $course->update($validated);

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'updated_course',
            "Updated course: {$course->course_name}",
            'Course',
            $course->id
        );

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Delete course (Staff only)
     */
    public function destroy(Course $course)
    {
        $courseName = $course->course_name;
        $course->delete();

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'deleted_course',
            "Deleted course: {$courseName}",
            'Course',
            $course->id
        );

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }
}
