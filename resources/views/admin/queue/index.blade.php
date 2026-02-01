@extends('adminlte::page')

@section('title', 'Process Queue')

@section('content_header')
    <h1><i class="fas fa-tasks"></i> Process Queue</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p class="mb-3">
                After you <strong>Bulk Upload</strong> a CSV, jobs are added to the queue. Click the button below to run the queue worker so pending jobs (e.g. your import) are processed on the server.
            </p>
            <p class="text-muted small mb-3">
                This runs <code>php artisan queue:work --stop-when-empty</code> in the background. On live server it processes all pending jobs then stops.
            </p>

            @if(isset($pendingCount) && $pendingCount > 0)
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i> <strong>{{ $pendingCount }}</strong> job(s) pending in queue.
                </div>
            @endif

            <form action="{{ route('admin.queue.process') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-play"></i> Run queue worker now
                </button>
            </form>

            <a href="{{ route('admin.voters.upload') }}" class="btn btn-outline-secondary btn-lg ml-2">
                <i class="fas fa-upload"></i> Bulk Upload
            </a>
        </div>
    </div>
@stop
