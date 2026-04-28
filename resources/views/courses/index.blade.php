@extends('layouts.app')

@section('title', 'Courses - Student Management System')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="color: #2c3e50; margin-bottom: 0.5rem;">🎓 Courses</h1>
            <p style="color: #7f8c8d;">Manage and view all courses</p>
        </div>
        @if(auth()->user()->isStaff())
            <a href="{{ route('courses.create') }}" class="btn btn-success">+ Add Course</a>
        @endif
    </div>

    <div class="card">
        @if($courses->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Duration (hours)</th>
                        <th>Students</th>
                        <th>Description</th>
                        @if(auth()->user()->isStaff())
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                        <tr>
                            <td><strong>{{ $course->course_name }}</strong></td>
                            <td>{{ $course->duration }}</td>
                            <td>
                                <span style="background-color: #ecf0f1; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: 600;">
                                    {{ $course->students_count }}
                                </span>
                            </td>
                            <td>{{ Str::limit($course->description, 50) }}</td>
                            @if(auth()->user()->isStaff())
                                <td>
                                    <div class="btn-group" style="gap: 0.25rem;">
                                        <a href="{{ route('courses.edit', $course) }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Edit</a>
                                        <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display: inline;">
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
        @else
            <div style="text-align: center; padding: 3rem;">
                <p style="color: #7f8c8d; font-size: 1.1rem; margin-bottom: 1rem;">📭 No courses found</p>
                @if(auth()->user()->isStaff())
                    <a href="{{ route('courses.create') }}" class="btn btn-success">Add your first course</a>
                @endif
            </div>
        @endif
    </div>

    @if(!auth()->user()->isStaff())
        <div class="card" style="background-color: #d1ecf1; border-left: 4px solid #0c5460;">
            <p style="color: #0c5460; margin: 0;">
                <strong>ℹ️ Read-Only Access:</strong> You have view-only access to course data. To add, edit, or delete courses, contact a Staff member.
            </p>
        </div>
    @endif
@endsection
