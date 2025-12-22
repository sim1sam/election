@extends('adminlte::page')

@section('title', 'Voter Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-users"></i> Voter Management</h1>
        <div>
            <a href="{{ route('admin.voters.download-template') }}" class="btn btn-info mr-2">
                <i class="fas fa-download"></i> Download CSV Template
            </a>
            <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#csvUploadModal">
                <i class="fas fa-upload"></i> Bulk Upload CSV
            </button>
            <a href="{{ route('admin.voters.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Voter
            </a>
        </div>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Import Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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

    <!-- CSV Upload Modal -->
    <div class="modal fade" id="csvUploadModal" tabindex="-1" role="dialog" aria-labelledby="csvUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="csvUploadModalLabel">
                        <i class="fas fa-upload"></i> Bulk Upload Voters from CSV
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.voters.import-csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Instructions:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Download the CSV template first to see the correct format</li>
                                <li>Required columns: Name, Voter Number</li>
                                <li><strong>Important:</strong> If your voter number starts with 0 or has leading zeros, prefix it with a single quote (') in Excel to preserve the zeros. Example: '001234 or '261000000000</li>
                                <li>Date format: YYYY-MM-DD, DD/MM/YYYY, M/D/YYYY, or DD-MM-YYYY (e.g., 1990-01-15, 15/01/1990, 1/15/1990)</li>
                                <li>Voter numbers must be unique</li>
                                <li>Maximum file size: 10MB</li>
                            </ul>
                        </div>
                        
                        <div class="form-group">
                            <label for="csv_file">Select CSV File</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('csv_file') is-invalid @enderror" 
                                       id="csv_file" name="csv_file" accept=".csv,.txt" required>
                                <label class="custom-file-label" for="csv_file">Choose file...</label>
                                @error('csv_file')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update file input label when file is selected
        document.getElementById('csv_file').addEventListener('change', function(e) {
            var fileName = e.target.files[0]?.name || 'Choose file...';
            e.target.nextElementSibling.textContent = fileName;
        });
    </script>
@stop
