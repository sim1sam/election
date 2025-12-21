@extends('adminlte::auth.auth-page', ['authType' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
@stop

@php
    $loginUrl = View::getSection('login_url') ?? config('adminlte.login_url', 'login');
    $registerUrl = View::getSection('register_url') ?? config('adminlte.register_url', 'register');
    $passResetUrl = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset');

    if (config('adminlte.use_route_url', false)) {
        $loginUrl = $loginUrl ? route($loginUrl) : '';
        $registerUrl = $registerUrl ? route($registerUrl) : '';
        $passResetUrl = $passResetUrl ? route($passResetUrl) : '';
    } else {
        $loginUrl = $loginUrl ? url($loginUrl) : '';
        $registerUrl = $registerUrl ? url($registerUrl) : '';
        $passResetUrl = $passResetUrl ? url($passResetUrl) : '';
    }
@endphp

@section('auth_header', __('adminlte::adminlte.login_message'))

@section('auth_body')
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #dc3545 0%, #28a745 100%);
            min-height: 100vh;
        }
        
        .login-box {
            width: 400px;
        }
        
        .login-logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            margin-bottom: 20px;
        }
        
        .login-logo a {
            color: #fff;
            text-decoration: none;
        }
        
        .login-logo a:hover {
            color: #fff;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border: none;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #dc3545 0%, #28a745 100%);
            color: #fff;
            border: none;
            padding: 25px;
            text-align: center;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .card-body {
            padding: 30px;
            background: #fff;
        }
        
        .input-group-text {
            background: linear-gradient(135deg, #dc3545 0%, #28a745 100%);
            color: #fff;
            border: none;
            border-radius: 5px 0 0 5px;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 0 5px 5px 0;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #dc3545 0%, #28a745 100%);
            border: none;
            border-radius: 5px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #c82333 0%, #218838 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .icheck-primary input[type="checkbox"]:checked + label::before {
            background-color: #28a745;
            border-color: #28a745;
        }
        
        .icheck-primary input[type="checkbox"]:checked + label::after {
            color: #fff;
        }
        
        .login-box-msg {
            color: #333;
            font-weight: 500;
            margin-bottom: 25px;
        }
        
        .text-danger {
            color: #dc3545 !important;
            font-weight: 500;
        }
        
        .alert {
            border-radius: 5px;
            border: none;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        a {
            color: #28a745;
            transition: color 0.3s ease;
        }
        
        a:hover {
            color: #dc3545;
            text-decoration: none;
        }
        
        .login-page {
            background: linear-gradient(135deg, #dc3545 0%, #28a745 100%);
        }
        
        .login-page::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(220, 53, 69, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(40, 167, 69, 0.3) 0%, transparent 50%);
            pointer-events: none;
        }
    </style>

    <form action="{{ $loginUrl }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                   placeholder="{{ __('adminlte::adminlte.password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Remember me --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary btn-block btn-flat">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>

    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($passResetUrl)
        <p class="my-0">
            <a href="{{ $passResetUrl }}" style="color: #28a745;">
                <i class="fas fa-key"></i> {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    @if($registerUrl)
        <p class="my-0">
            <a href="{{ $registerUrl }}" style="color: #dc3545;">
                <i class="fas fa-user-plus"></i> {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif
@stop
