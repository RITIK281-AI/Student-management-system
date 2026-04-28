@extends('layouts.app')

@section('title', 'Register - Student Management System')

@section('extra-css')
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 400px;
            margin: 0;
            padding: 0 1rem;
        }

        .register-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            padding: 2rem;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .register-header p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .info-box {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .register-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .register-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-register {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
@endsection

@section('content')
    <div class="register-card">
        <div class="register-header">
            <h1>📚 Register</h1>
            <p>Create a Staff Account</p>
        </div>

        <div class="info-box">
            <strong>Note:</strong> Only Staff accounts can be registered. Admin and CEO accounts are created by administrators.
        </div>

        <form action="{{ route('register') }}" method="POST" id="registerForm">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    placeholder="Enter your full name"
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    required
                    placeholder="Enter your email"
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    placeholder="Enter a password (min 6 characters)"
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required
                    placeholder="Confirm your password"
                >
            </div>

            <button type="submit" class="btn-register">Register</button>
        </form>

        <div class="register-footer">
            Already have an account? <a href="{{ route('login') }}">Login here</a>
        </div>
    </div>
@endsection
