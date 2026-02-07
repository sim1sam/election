@extends('adminlte::page')

@section('title', 'Admin Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <style>
        .dashboard-page { background: linear-gradient(160deg, #f0f9ff 0%, #e0f2fe 50%, #bae6fd 100%); min-height: 70vh; margin: 0 -15px; padding: 0 15px 2rem; border-radius: 0; }
        @media (min-width: 768px) { .dashboard-page { margin: 0 -1rem; padding: 0 1rem 2rem; } }
        .dashboard-page .content-header { background: transparent; }
        .dashboard-page .breadcrumb { background: transparent; }
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.15);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(14, 165, 233, 0.22); }
        .stat-card .card-body { padding: 1.25rem 1.5rem; }
        .stat-card .stat-value { font-size: 1.75rem; font-weight: 700; color: #0c4a6e; line-height: 1.2; }
        .stat-card .stat-label { color: #0369a1; font-size: 0.9rem; margin-top: 0.25rem; }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; color: #fff;
        }
        .stat-card.stat-voters .stat-icon { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
        .stat-card.stat-popups .stat-icon { background: linear-gradient(135deg, #06b6d4, #0891b2); }
        .stat-card.stat-users .stat-icon { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
        .stat-card.stat-admins .stat-icon { background: linear-gradient(135deg, #0d9488, #0f766e); }
        .quick-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.12);
            transition: all 0.2s;
            text-decoration: none;
            color: inherit;
            display: block;
            background: rgba(255,255,255,0.85);
            border-left: 4px solid #0ea5e9;
        }
        .quick-card:hover { color: inherit; box-shadow: 0 8px 24px rgba(14, 165, 233, 0.2); transform: translateX(4px); }
        .quick-card .card-body { padding: 1rem 1.25rem; }
        .quick-card .quick-title { font-weight: 600; color: #0c4a6e; font-size: 1rem; }
        .quick-card .quick-desc { font-size: 0.8rem; color: #64748b; margin-top: 0.25rem; }
        .welcome-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(14, 165, 233, 0.15);
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(224,242,254,0.5) 100%);
            border: 1px solid rgba(14, 165, 233, 0.2);
        }
        .welcome-card .card-header {
            background: linear-gradient(90deg, rgba(14,165,233,0.12), transparent);
            border-bottom: 1px solid rgba(14, 165, 233, 0.2);
            font-weight: 600;
            color: #0c4a6e;
        }
        .welcome-card .info-row { padding: 0.5rem 0; border-bottom: 1px solid rgba(14,165,233,0.08); }
        .welcome-card .info-row:last-child { border-bottom: none; }
    </style>

    <div class="dashboard-page">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card stat-card stat-voters">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3"><i class="fas fa-users"></i></div>
                        <div>
                            <div class="stat-value">{{ number_format($stats['voters_total']) }}</div>
                            <div class="stat-label">মোট ভোটার (Voters)</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.voters.index') }}" class="small-box-footer text-center py-2" style="background: rgba(14,165,233,0.08); color: #0369a1;">
                        ভোটার তালিকা <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card stat-card stat-popups">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3"><i class="fas fa-image"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['popups_active'] }} / {{ $stats['popups_total'] }}</div>
                            <div class="stat-label">পপআপ (সক্রিয়/মোট)</div>
                        </div>
                    </div>
                    <a href="{{ route('admin.popups.index') }}" class="small-box-footer text-center py-2" style="background: rgba(6,182,212,0.1); color: #0891b2;">
                        পপআপ ব্যবস্থাপনা <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card stat-card stat-users">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3"><i class="fas fa-user-friends"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['users_total'] }}</div>
                            <div class="stat-label">মোট ব্যবহারকারী</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
                <div class="card stat-card stat-admins">
                    <div class="card-body d-flex align-items-center">
                        <div class="stat-icon mr-3"><i class="fas fa-user-shield"></i></div>
                        <div>
                            <div class="stat-value">{{ $stats['admins_total'] }}</div>
                            <div class="stat-label">অ্যাডমিন</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card welcome-card">
                    <div class="card-header">
                        <i class="fas fa-info-circle mr-2"></i> অ্যাডমিন প্যানেলে স্বাগতম
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-row">
                                    <strong><i class="fas fa-user text-info"></i> লগইন ব্যবহারকারী:</strong><br>
                                    <span class="text-muted">{{ auth()->user()->name }}</span>
                                </div>
                                <div class="info-row">
                                    <strong><i class="fas fa-envelope text-info"></i> ইমেইল:</strong><br>
                                    <span class="text-muted">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-row">
                                    <strong><i class="fas fa-user-shield text-info"></i> ভূমিকা:</strong><br>
                                    <span class="badge badge-primary">{{ ucfirst(auth()->user()->role) }}</span>
                                </div>
                                <div class="info-row">
                                    <strong><i class="fas fa-calendar text-info"></i> অ্যাকাউন্ট তৈরি:</strong><br>
                                    <span class="text-muted">{{ auth()->user()->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header" style="background: linear-gradient(90deg, rgba(14,165,233,0.12), transparent); border-bottom: 1px solid rgba(14,165,233,0.2); font-weight: 600; color: #0c4a6e;">
                        <i class="fas fa-bolt mr-2"></i> দ্রুত লিংক
                    </div>
                    <div class="card-body p-2">
                        <a href="{{ route('admin.voters.index') }}" class="card quick-card mb-2">
                            <div class="card-body">
                                <span class="quick-title"><i class="fas fa-users text-info mr-2"></i> ভোটার ব্যবস্থাপনা</span>
                                <div class="quick-desc">তালিকা, খোঁজ, সম্পাদনা</div>
                            </div>
                        </a>
                        <a href="{{ route('admin.voters.upload') }}" class="card quick-card mb-2">
                            <div class="card-body">
                                <span class="quick-title"><i class="fas fa-upload text-success mr-2"></i> বাল্ক আপলোড (CSV)</span>
                                <div class="quick-desc">একসাথে ভোটার যোগ করুন</div>
                            </div>
                        </a>
                        <a href="{{ route('admin.popups.index') }}" class="card quick-card">
                            <div class="card-body">
                                <span class="quick-title"><i class="fas fa-image text-secondary mr-2"></i> পপআপ ব্যবস্থাপনা</span>
                                <div class="quick-desc">ক্যান্ডিডেট/আইকন ইমেজ ও বার্তা</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
