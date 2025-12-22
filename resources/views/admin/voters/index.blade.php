@extends('adminlte::page')

@section('title', 'Voter Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users"></i> Voter Management</h1>
        <a href="{{ route('admin.voters.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Voter
        </a>
    </div>
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

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>নাম</th>
                            <th>ভোটার নম্বর</th>
                            <th>পিতা</th>
                            <th>মাতা</th>
                            <th>পেশা</th>
                            <th>ঠিকানা</th>
                            <th>ভোট কেন্দ্র</th>
                            <th>ওয়ার্ড নম্বর</th>
                            <th>জন্ম তারিখ</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($voters as $voter)
                            <tr>
                                <td>{{ $voter->id }}</td>
                                <td>{{ $voter->name }}</td>
                                <td><strong>{{ $voter->voter_number }}</strong></td>
                                <td>{{ $voter->father_name ?? '-' }}</td>
                                <td>{{ $voter->mother_name ?? '-' }}</td>
                                <td>{{ $voter->occupation ?? '-' }}</td>
                                <td>{{ Str::limit($voter->address, 30) ?? '-' }}</td>
                                <td>{{ $voter->polling_center_name ?? '-' }}</td>
                                <td>{{ $voter->ward_number ?? '-' }}</td>
                                <td>{{ $voter->date_of_birth ? $voter->date_of_birth->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.voters.edit', $voter) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.voters.destroy', $voter) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this voter?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">No voters found. <a href="{{ route('admin.voters.create') }}">Add one</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-3">
                {{ $voters->links() }}
            </div>
        </div>
    </div>
@stop
