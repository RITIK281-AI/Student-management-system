<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show staff dashboard with analytics
     */
    public function staffDashboard()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $averageMarks = Student::avg('marks') ?? 0;
        
        // Students per course
        $studentsPerCourse = Course::withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(5)
            ->get();

        // Grade distribution
        $gradeDistribution = Student::select('grade', DB::raw('count(*) as count'))
            ->groupBy('grade')
            ->get();

        // Recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Recent students
        $recentStudents = Student::with('course')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboards.staff', compact(
            'totalStudents',
            'totalCourses',
            'averageMarks',
            'studentsPerCourse',
            'gradeDistribution',
            'recentActivities',
            'recentStudents'
        ));
    }

    /**
     * Show admin dashboard (read-only)
     */
    public function adminDashboard()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $averageMarks = Student::avg('marks') ?? 0;

        // Students per course
        $studentsPerCourse = Course::withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(5)
            ->get();

        // Grade distribution
        $gradeDistribution = Student::select('grade', DB::raw('count(*) as count'))
            ->groupBy('grade')
            ->get();

        // Recent activities
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboards.admin', compact(
            'totalStudents',
            'totalCourses',
            'averageMarks',
            'studentsPerCourse',
            'gradeDistribution',
            'recentActivities'
        ));
    }

    /**
     * Show CEO dashboard (read-only)
     */
    public function ceoDashboard()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $averageMarks = Student::avg('marks') ?? 0;

        // Students per course
        $studentsPerCourse = Course::withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(5)
            ->get();

        // Grade distribution
        $gradeDistribution = Student::select('grade', DB::raw('count(*) as count'))
            ->groupBy('grade')
            ->get();

        // Pass/Fail statistics
        $passCount = Student::where('marks', '>=', 40)->count();
        $failCount = Student::where('marks', '<', 40)->count();

        return view('dashboards.ceo', compact(
            'totalStudents',
            'totalCourses',
            'averageMarks',
            'studentsPerCourse',
            'gradeDistribution',
            'passCount',
            'failCount'
        ));
    }

    /**
     * Get analytics data for charts (JSON)
     */
    public function getAnalyticsData()
    {
        $gradeDistribution = Student::select('grade', DB::raw('count(*) as count'))
            ->groupBy('grade')
            ->get();

        $studentsPerCourse = Course::withCount('students')
            ->orderBy('students_count', 'desc')
            ->get();

        return response()->json([
            'gradeDistribution' => $gradeDistribution,
            'studentsPerCourse' => $studentsPerCourse,
        ]);
    }
}
