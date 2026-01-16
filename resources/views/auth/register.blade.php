@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white text-center">
                <h4 class="mb-0">Create an Account</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('register.submit') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Register</button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <small>Already have an account? <a href="{{ route('login') }}">Login here</a>.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background: linear-gradient(135deg, #f1f8e9, #c8e6c9);
        font-family: 'Segoe UI', Tahoma, sans-serif;
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        background: #43a047 !important;
        color: #fff;
        font-weight: bold;
        letter-spacing: 0.5px;
    }

    .btn-success {
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-success:hover {
        background-color: #388e3c;
        transform: scale(1.05);
    }

    .form-control:focus {
        border-color: #43a047;
        box-shadow: 0 0 5px rgba(67, 160, 71, 0.5);
    }

    .alert {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .register-link a {
        color: #43a047;
        font-weight: 600;
        text-decoration: none;
    }

    .register-link a:hover {
        text-decoration: underline;
    }
</style>

@endsection