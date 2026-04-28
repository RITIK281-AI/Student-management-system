@extends('layouts.app')

@section('title', 'Login - Student Management System')

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

        .login-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            padding: 2rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .login-header p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .demo-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
@endsection

@section('content')
    <div class="login-card">
        <div class="login-header">
            <h1>📚 Login</h1>
            <p>Student Management System</p>
        </div>

        <div class="demo-info">
            <strong>Demo Credentials:</strong><br>
            Admin: admin@example.com / password<br>
            CEO: ceo@example.com / password<br>
            Staff: staff@example.com / password<br>
            Or register as Staff
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

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
                    placeholder="Enter your password"
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="login-footer">
            Don't have an account? <a href="{{ route('register') }}">Register here</a>
        </div>
    </div>
@endsection
