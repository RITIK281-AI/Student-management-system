@extends('layouts.app')

@section('title', 'Edit Student - Student Management System')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #2c3e50; margin-bottom: 0.5rem;">✏️ Edit Student</h1>
        <p style="color: #7f8c8d;">Update student information</p>
    </div>

    <div class="card" style="max-width: 600px;">
        <form action="{{ route('students.update', $student) }}" method="POST" id="studentForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Student Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $student->name) }}"
                    required
                    placeholder="Enter student's full name"
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $student->email) }}"
                    required
                    placeholder="Enter student's email"
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="course_id">Course *</label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Select a Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="marks">Marks (0-100) *</label>
                <input 
                    type="number" 
                    id="marks" 
                    name="marks" 
                    value="{{ old('marks', $student->marks) }}"
                    required
                    min="0"
                    max="100"
                    placeholder="Enter marks"
                >
                @error('marks')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-success">✅ Update Student</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">❌ Cancel</a>
            </div>
        </form>
    </div>

    <div class="card" style="max-width: 600px; background-color: #f9f9f9;">
        <div class="card-title" style="color: #2c3e50;">📋 Current Information</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div>
                <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Current Grade</p>
                <p style="color: #2c3e50; font-weight: 600;">{{ $student->grade }}</p>
            </div>
            <div>
                <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Status</p>
                <p style="color: #2c3e50; font-weight: 600;">{{ $student->marks >= 40 ? 'Pass' : 'Fail' }}</p>
            </div>
            <div>
                <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Created</p>
                <p style="color: #2c3e50; font-weight: 600;">{{ $student->created_at->format('M d, Y') }}</p>
            </div>
            <div>
                <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 0.25rem;">Last Updated</p>
                <p style="color: #2c3e50; font-weight: 600;">{{ $student->updated_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>
@endsection
