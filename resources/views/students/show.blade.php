@extends('layouts.app')

@section('title', $student->name . ' - Student Profile')

@section('content')
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('students.index') }}" style="color: #667eea; text-decoration: none;">← Back to Students</a>
        <h1 style="color: #2c3e50; margin-bottom: 0.5rem; margin-top: 1rem;">👤 {{ $student->name }}</h1>
        <p style="color: #7f8c8d;">Student Profile</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- Basic Information -->
        <div class="card">
            <div class="card-title">📋 Basic Information</div>
            <div style="display: grid; gap: 1rem;">
                <div>
                    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Name</p>
                    <p style="color: #2c3e50; font-weight: 600;">{{ $student->name }}</p>
                </div>
                <div>
                    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Email</p>
                    <p style="color: #2c3e50; font-weight: 600;">{{ $student->email }}</p>
                </div>
                <div>
                    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Course</p>
                    <p style="color: #2c3e50; font-weight: 600;">{{ $student->course->course_name }}</p>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="card">
            <div class="card-title">🎓 Academic Information</div>
            <div style="display: grid; gap: 1rem;">
                <div>
                    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Marks</p>
                    <p style="color: #2c3e50; font-weight: 600; font-size: 1.5rem;">{{ $student->marks }}/100</p>
                </div>
                <div>
                    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Grade</p>
                    <p style="color: #2c3e50; font-weight: 600; font-size: 1.5rem;">{{ $student->grade }}</p>
                </div>
                <div>
                    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Status</p>
                    @if($student->marks >= 40)
                        <p style="color: #27ae60; font-weight: 600; font-size: 1.2rem;">✓ Pass</p>
                    @else
                        <p style="color: #e74c3c; font-weight: 600; font-size: 1.2rem;">✗ Fail</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dates -->
        <div class="card">
            <div class="card-title">📅 Dates</div>
            <div style="display: grid; gap: 1rem;">
                <div>
                    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Enrolled</p>
                    <p style="color: #2c3e50; font-weight: 600;">{{ $student->created_at->format('M d, Y H:i') }}</p>
                </div>
                <div>
                    <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Last Updated</p>
                    <p style="color: #2c3e50; font-weight: 600;">{{ $student->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    @if(auth()->user()->isStaff())
        <div class="card" style="margin-top: 1.5rem;">
            <div class="btn-group">
                <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">✏️ Edit Student</a>
                <form action="{{ route('students.destroy', $student) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="confirmDelete(event)">🗑️ Delete Student</button>
                </form>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">← Back to List</a>
            </div>
        </div>
    @endif
@endsection
