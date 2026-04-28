@extends('layouts.app')

@section('title', 'Students - Student Management System')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: #2c3e50; margin-bottom: 0.5rem;">📚 Students</h1>
            <p style="color: #7f8c8d;">Manage and view all students</p>
        </div>
        @if(auth()->user()->isStaff())
            <div class="btn-group">
                <a href="{{ route('students.create') }}" class="btn btn-success">+ Add Student</a>
                <a href="{{ route('export.students.csv') }}" class="btn btn-primary">📥 Export CSV</a>
                <a href="{{ route('export.students.pdf') }}" class="btn btn-primary">📄 Export PDF</a>
            </div>
        @endif
    </div>

    <!-- Search and Filter -->
    <div class="card">
        <form action="{{ route('students.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="search">Search by Name</label>
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    placeholder="Enter student name..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="course_id">Filter by Course</label>
                <select id="course_id" name="course_id">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="grade">Filter by Grade</label>
                <select id="grade" name="grade">
                    <option value="">All Grades</option>
                    <option value="A" {{ request('grade') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ request('grade') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="C" {{ request('grade') == 'C' ? 'selected' : '' }}>C</option>
                    <option value="Fail" {{ request('grade') == 'Fail' ? 'selected' : '' }}>Fail</option>
                </select>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-primary">🔍 Search</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <!-- Students Table -->
    <div class="card">
        @if($students->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Marks</th>
                        <th>Grade</th>
                        <th>Status</th>
                        @if(auth()->user()->isStaff())
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td><a href="{{ route('students.show', $student) }}" style="color: #667eea; text-decoration: none;">{{ $student->name }}</a></td>
                            <td>{{ $student->email }}</td>
                            <td>{{ $student->course->course_name }}</td>
                            <td>{{ $student->marks }}/100</td>
                            <td>
                                <span style="background-color: #ecf0f1; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 600;">
                                    {{ $student->grade }}
                                </span>
                            </td>
                            <td>
                                @if($student->marks >= 40)
                                    <span style="color: #27ae60; font-weight: 600;">✓ Pass</span>
                                @else
                                    <span style="color: #e74c3c; font-weight: 600;">✗ Fail</span>
                                @endif
                            </td>
                            @if(auth()->user()->isStaff())
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
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 0.5rem; flex-wrap: wrap;">
                {{ $students->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 3rem;">
                <p style="color: #7f8c8d; font-size: 1.1rem; margin-bottom: 1rem;">📭 No students found</p>
                @if(auth()->user()->isStaff())
                    <a href="{{ route('students.create') }}" class="btn btn-success">Add your first student</a>
                @endif
            </div>
        @endif
    </div>

    <!-- Info for non-staff -->
    @if(!auth()->user()->isStaff())
        <div class="card" style="background-color: #d1ecf1; border-left: 4px solid #0c5460;">
            <p style="color: #0c5460; margin: 0;">
                <strong>ℹ️ Read-Only Access:</strong> You have view-only access to student data. To add, edit, or delete students, contact a Staff member.
            </p>
        </div>
    @endif
@endsection
