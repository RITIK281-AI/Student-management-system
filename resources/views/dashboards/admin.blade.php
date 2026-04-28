@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #2c3e50; margin-bottom: 0.5rem;">👋 Welcome, {{ auth()->user()->name }}!</h1>
        <p style="color: #7f8c8d;">Admin Dashboard - View-only access to all data</p>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2rem; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $totalStudents }}</div>
            <div style="font-size: 1rem; opacity: 0.9;">Total Students</div>
        </div>
        <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 2rem; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $totalCourses }}</div>
            <div style="font-size: 1rem; opacity: 0.9;">Total Courses</div>
        </div>
        <div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 2rem; border-radius: 8px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.5rem;">{{ round($averageMarks, 2) }}</div>
            <div style="font-size: 1rem; opacity: 0.9;">Average Marks</div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="card" style="background-color: #d1ecf1; border-left: 4px solid #0c5460;">
        <p style="color: #0c5460; margin: 0;">
            <strong>ℹ️ Admin Access:</strong> You have read-only access to all student and course data. To make changes, contact a Staff member.
        </p>
    </div>

    <!-- Grade Distribution -->
    <div class="card">
        <div class="card-title">📈 Grade Distribution</div>
        <table>
            <thead>
                <tr>
                    <th>Grade</th>
                    <th>Count</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                @php $total = $gradeDistribution->sum('count'); @endphp
                @foreach($gradeDistribution as $grade)
                    <tr>
                        <td>{{ $grade->grade }}</td>
                        <td>{{ $grade->count }}</td>
                        <td>{{ round(($grade->count / $total) * 100, 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Students Per Course -->
    <div class="card">
        <div class="card-title">📊 Students Per Course</div>
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Number of Students</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentsPerCourse as $course)
                    <tr>
                        <td>{{ $course->course_name }}</td>
                        <td>
                            <span style="background-color: #ecf0f1; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 600;">
                                {{ $course->students_count }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Recent Activities -->
    <div class="card">
        <div class="card-title">📝 Recent Activities</div>
        @if($recentActivities->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivities as $activity)
                        <tr>
                            <td>{{ $activity->user->name }}</td>
                            <td><span style="background-color: #ecf0f1; padding: 0.25rem 0.75rem; border-radius: 4px;">{{ $activity->action }}</span></td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #7f8c8d; text-align: center; padding: 2rem;">No recent activities</p>
        @endif
    </div>
@endsection
