@extends('layouts.app')

@section('title', 'Add Course - Student Management System')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="color: #2c3e50; margin-bottom: 0.5rem;">➕ Add New Course</h1>
        <p style="color: #7f8c8d;">Create a new course</p>
    </div>

    <div class="card" style="max-width: 600px;">
        <form action="{{ route('courses.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="course_name">Course Name *</label>
                <input 
                    type="text" 
                    id="course_name" 
                    name="course_name" 
                    value="{{ old('course_name') }}"
                    required
                    placeholder="Enter course name"
                >
                @error('course_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea 
                    id="description" 
                    name="description" 
                    placeholder="Enter course description"
                    rows="4"
                >{{ old('description') }}</textarea>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="duration">Duration (hours) *</label>
                <input 
                    type="number" 
                    id="duration" 
                    name="duration" 
                    value="{{ old('duration', 0) }}"
                    required
                    min="1"
                    placeholder="Enter duration in hours"
                >
                @error('duration')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-success">✅ Create Course</button>
                <a href="{{ route('courses.index') }}" class="btn btn-secondary">❌ Cancel</a>
            </div>
        </form>
    </div>
@endsection
