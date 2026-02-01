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
        body.login-page {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #E0F7FA 0%, #B3E5FC 35%, #81D4FA 70%, #4FC3F7 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            color: #0c4a6e;
        }
        body.login-page::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                105deg,
                transparent 0px,
                transparent 60px,
                rgba(255,255,255,0.25) 60px,
                rgba(255,255,255,0.25) 62px
            );
            pointer-events: none;
            z-index: 0;
        }
        body.login-page::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                -75deg,
                transparent 0px,
                transparent 80px,
                rgba(14,165,233,0.08) 80px,
                rgba(14,165,233,0.08) 83px
            );
            pointer-events: none;
            z-index: 0;
        }
        
        .login-page .login-box {
            position: relative;
            z-index: 1;
        }
        
        .login-box {
            width: 400px;
        }
        
        .login-logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #0c4a6e;
            text-shadow: none;
            margin-bottom: 20px;
        }
        
        .login-logo a {
            color: #0c4a6e;
            text-decoration: none;
        }
        
        .login-logo a:hover {
            color: #0369a1;
        }
        
        .login-page .card {
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(14, 165, 233, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.85);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
        }
        
        .login-page .card-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            color: #fff;
            border: none;
            padding: 25px;
            text-align: center;
        }
        
        .login-page .card-header .card-title,
        .login-page .card-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
            color: #fff;
        }
        
        .login-page .card-body {
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
        }
        
        .login-page .card-footer {
            background: rgba(255, 255, 255, 0.85);
            border-top: 1px solid rgba(14, 165, 233, 0.2);
        }
        
        .login-page .input-group-text {
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            color: #fff;
            border: none;
            border-radius: 5px 0 0 5px;
        }
        
        .login-page .form-control {
            border: 2px solid rgba(14, 165, 233, 0.3);
            border-radius: 0 5px 5px 0;
            padding: 12px 15px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            color: #0c4a6e;
        }
        
        .login-page .form-control:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }
        
        .login-page .form-control::placeholder {
            color: rgba(12, 74, 110, 0.5);
        }
        
        .input-group {
            margin-bottom: 20px;
        }
        
        .login-page .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%);
            border: none;
            border-radius: 5px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.35);
        }
        
        .login-page .btn-primary:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(14, 165, 233, 0.45);
        }
        
        .login-page .btn-primary:active {
            transform: translateY(0);
        }
        
        .login-page .icheck-primary input[type="checkbox"]:checked + label::before {
            background-color: #0ea5e9;
            border-color: #0ea5e9;
        }
        
        .login-page .icheck-primary input[type="checkbox"]:checked + label::after {
            color: #fff;
        }
        
        .login-page .icheck-primary label {
            color: #0c4a6e;
        }
        
        .login-box-msg {
            color: #0c4a6e;
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
        
        .login-page a {
            color: #0369a1;
            transition: color 0.3s ease;
        }
        
        .login-page a:hover {
            color: #0ea5e9;
            text-decoration: none;
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
@stop
