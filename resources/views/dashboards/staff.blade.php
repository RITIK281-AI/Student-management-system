@extends('layouts.app')

@section('title', 'Staff Dashboard - Student Management System')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #2c3e50; margin-bottom: 0.5rem;">👋 Welcome, {{ auth()->user()->name }}!</h1>
        <p style="color: #7f8c8d;">Staff Dashboard - Manage students and courses</p>
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
            <div class="stat-label">Logged in as Staff</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <div class="card-title">🚀 Quick Actions</div>
        <div class="btn-group">
            <a href="{{ route('students.create') }}" class="btn btn-success">+ Add New Student</a>
            <a href="{{ route('students.index') }}" class="btn btn-primary">View All Students</a>
        </div>
    </div>

    <!-- Recent Students -->
    <div class="card">
        <div class="card-title">📋 Recently Added Students</div>
        @if($recentStudents->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Marks</th>
                        <th>Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentStudents as $student)
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->course->course_name }}</td>
                            <td>{{ $student->marks }}/100</td>
                            <td>{{ $student->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" style="gap: 0.25rem;">
                                    <a href="{{ route('students.edit', $student) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Edit</a>
                                    <form action="{{ route('students.destroy', $student) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.9rem;" onclick="confirmDelete(event)">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="color: #7f8c8d; text-align: center; padding: 2rem;">No students added yet. <a href="{{ route('students.create') }}">Add your first student</a></p>
        @endif
    </div>

    <!-- Features Overview -->
    <div class="card">
        <div class="card-title">✨ Your Capabilities</div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            <div style="padding: 1rem; background-color: #f9f9f9; border-radius: 4px; border-left: 4px solid #3498db;">
                <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">📝 Full CRUD Access</h3>
                <p style="color: #7f8c8d; font-size: 0.9rem;">Create, read, update, and delete student records</p>
            </div>
            <div style="padding: 1rem; background-color: #f9f9f9; border-radius: 4px; border-left: 4px solid #27ae60;">
                <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">🎓 Course Assignment</h3>
                <p style="color: #7f8c8d; font-size: 0.9rem;">Assign and manage student courses</p>
            </div>
            <div style="padding: 1rem; background-color: #f9f9f9; border-radius: 4px; border-left: 4px solid #e74c3c;">
                <h3 style="color: #2c3e50; margin-bottom: 0.5rem;">📊 Marks Management</h3>
                <p style="color: #7f8c8d; font-size: 0.9rem;">Record and update student marks</p>
            </div>
        </div>
    </div>
@endsection
