@extends('layouts.app')

@section('title', 'Add Student - Student Management System')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #2c3e50; margin-bottom: 0.5rem;">➕ Add New Student</h1>
        <p style="color: #7f8c8d;">Fill in the form below to add a new student</p>
    </div>

    <div class="card" style="max-width: 600px;">
        <form action="{{ route('students.store') }}" method="POST" id="studentForm">
            @csrf

            <!-- Name Field -->
            <div class="form-group">
                <label for="name">Student Name *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    placeholder="Enter student's full name"
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    placeholder="Enter student's email"
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Course Selection -->
            <div class="form-group">
                <label for="course_id">Course *</label>
                <select id="course_id" name="course_id" required>
                    <option value="">-- Select a Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
                @error('course_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Marks Field -->
            <div class="form-group">
                <label for="marks">Marks (0-100) *</label>
                <input 
                    type="number" 
                    id="marks" 
                    name="marks" 
                    value="{{ old('marks', 0) }}"
                    required
                    min="0"
                    max="100"
                    placeholder="Enter marks"
                >
                @error('marks')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="btn-group">
                <button type="submit" class="btn btn-success">✅ Add Student</button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">❌ Cancel</a>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="card" style="max-width: 600px; background-color: #f0f8ff; border-left: 4px solid #3498db;">
        <div class="card-title" style="color: #2c3e50;">💡 Tips</div>
        <ul style="color: #555; margin-left: 1.5rem;">
            <li>All fields are required</li>
            <li>Email must be unique for each student</li>
            <li>Marks should be between 0 and 100</li>
            <li>Select a course from the dropdown</li>
        </ul>
    </div>
@endsection
