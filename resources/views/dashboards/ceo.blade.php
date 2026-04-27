@extends('layouts.app')

@section('title', 'CEO Dashboard - Student Management System')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #2c3e50; margin-bottom: 0.5rem;">👋 Welcome, {{ auth()->user()->name }}!</h1>
        <p style="color: #7f8c8d;">CEO Dashboard - View-only access to all data</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-number">{{ $totalStudents }}</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card green">
            <div class="stat-number">{{ $totalCourses }}</div>
            <div class="stat-label">Total Courses</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-number">{{ auth()->user()->name }}</div>
            <div class="stat-label">Logged in as CEO</div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="card" style="background-color: #d1ecf1; border-left: 4px solid #0c5460;">
        <p style="color: #0c5460; margin: 0;">
            <strong>ℹ️ CEO Access:</strong> You have read-only access to all student and course data for reporting purposes. 
            To make changes, contact a Staff member.
        </p>
    </div>

    <!-- All Students -->
    <div class="card">
        <div class="card-title">📋 All Students</div>
        
        @if($students->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Marks</th>
                        <th>Enrolled</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->course->course_name }}</td>
                            <td>{{ $student->marks }}/100</td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                {{ $students->links() }}
            </div>
        @else
            <p style="color: #7f8c8d; text-align: center; padding: 2rem;">No students found.</p>
        @endif
    </div>

    <!-- Permissions Overview -->
    <div class="card">
        <div class="card-title">🔒 Your Permissions</div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div style="padding: 1rem; background-color: #d4edda; border-radius: 4px; border-left: 4px solid #27ae60;">
                <h3 style="color: #155724; margin-bottom: 0.5rem;">✅ Allowed</h3>
                <ul style="color: #155724; font-size: 0.9rem; margin: 0; padding-left: 1.5rem;">
                    <li>View all students</li>
                    <li>View all courses</li>
                    <li>Search students</li>
                    <li>Filter by course</li>
                </ul>
            </div>
            <div style="padding: 1rem; background-color: #f8d7da; border-radius: 4px; border-left: 4px solid #e74c3c;">
                <h3 style="color: #721c24; margin-bottom: 0.5rem;">❌ Not Allowed</h3>
                <ul style="color: #721c24; font-size: 0.9rem; margin: 0; padding-left: 1.5rem;">
                    <li>Add students</li>
                    <li>Edit student data</li>
                    <li>Delete students</li>
                    <li>Modify courses</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
