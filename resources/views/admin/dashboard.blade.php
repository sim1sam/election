@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ \App\Models\User::count() }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ \App\Models\User::where('role', 'admin')->count() }}</h3>
                    <p>Admin Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ \App\Models\User::where('role', 'user')->count() }}</h3>
                    <p>Regular Users</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>0</h3>
                    <p>Other Stats</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Welcome to Admin Panel</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user"></i> Logged in as:</strong></p>
                            <p class="text-muted">{{ auth()->user()->name }}</p>
                            <p><strong><i class="fas fa-envelope"></i> Email:</strong></p>
                            <p class="text-muted">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="fas fa-user-shield"></i> Role:</strong></p>
                            <p><span class="badge badge-primary badge-lg">{{ ucfirst(auth()->user()->role) }}</span></p>
                            <p><strong><i class="fas fa-calendar"></i> Last Login:</strong></p>
                            <p class="text-muted">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-pie"></i> Quick Stats</h3>
                </div>
                <div class="card-body">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Users</span>
                            <span class="info-box-number">{{ \App\Models\User::count() }}</span>
                        </div>
                    </div>
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-shield"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Admins</span>
                            <span class="info-box-number">{{ \App\Models\User::where('role', 'admin')->count() }}</span>
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Regular Users</span>
                            <span class="info-box-number">{{ \App\Models\User::where('role', 'user')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        console.log('AdminLTE Dashboard Loaded Successfully!');
    </script>
@stop

