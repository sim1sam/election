@extends('adminlte::page')

@section('title', 'Voter Management')

@section('content_header')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
        <h1><i class="fas fa-users"></i> Voter Management</h1>
        <div class="mt-2 mt-md-0 d-flex flex-wrap gap-2">
            <a href="{{ route('admin.voters.download-template') }}" class="btn btn-info btn-sm">
                <i class="fas fa-download"></i> <span class="d-none d-md-inline">Download CSV Template</span>
                <span class="d-md-none">Template</span>
            </a>
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#csvUploadModal">
                <i class="fas fa-upload"></i> <span class="d-none d-md-inline">Bulk Upload CSV</span>
                <span class="d-md-none">Upload</span>
            </button>
            <a href="{{ route('admin.voters.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> <span class="d-none d-md-inline">Add New Voter</span>
                <span class="d-md-none">Add</span>
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

    <!-- Search Card -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-search"></i> Search Voters</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.voters.index') }}" class="row">
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="name">নাম (Name)</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               class="form-control" 
                               value="{{ request('name') }}" 
                               placeholder="Enter name (similar names will be shown)">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="voter_number">ভোটার নম্বর (Voter Number)</label>
                        <input type="text" 
                               name="voter_number" 
                               id="voter_number" 
                               class="form-control" 
                               value="{{ request('voter_number') }}" 
                               placeholder="Enter voter number">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="voter_area_number">ভোটার এলাকার নম্বর (Voter Area Number)</label>
                        <input type="text" 
                               name="voter_area_number" 
                               id="voter_area_number" 
                               class="form-control" 
                               value="{{ request('voter_area_number') }}" 
                               placeholder="Enter voter area number">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="ward_number">ওয়ার্ড নম্বর (Ward Number)</label>
                        <input type="text" 
                               name="ward_number" 
                               id="ward_number" 
                               class="form-control" 
                               value="{{ request('ward_number') }}" 
                               placeholder="Enter ward number">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="voter_serial_number">ভোটার সিরিয়াল নম্বর (Voter Serial Number)</label>
                        <input type="text" 
                               name="voter_serial_number" 
                               id="voter_serial_number" 
                               class="form-control" 
                               value="{{ request('voter_serial_number') }}" 
                               placeholder="Enter voter serial number">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="form-group">
                        <label for="date_of_birth">জন্ম তারিখ (Date of Birth)</label>
                        <input type="date" 
                               name="date_of_birth" 
                               id="date_of_birth" 
                               class="form-control" 
                               value="{{ request('date_of_birth') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block btn-sm">
                            <i class="fas fa-search"></i> Search
                        </button>
                        @if(request()->hasAny(['name', 'voter_number', 'voter_area_number', 'ward_number', 'voter_serial_number', 'date_of_birth']))
                            <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary btn-block btn-sm mt-2">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if(request()->hasAny(['name', 'voter_number', 'voter_area_number', 'ward_number', 'voter_serial_number', 'date_of_birth']))
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i> 
                    Showing search results. 
                    <a href="{{ route('admin.voters.index') }}" class="alert-link">View all voters</a>
                </div>
            @endif
            
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
                            <th>ভোটার এলাকার নম্বর</th>
                            <th>ভোটার সিরিয়াল নম্বর</th>
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
                                <td>{{ $voter->address ? \Illuminate\Support\Str::limit($voter->address, 30) : '-' }}</td>
                                <td>{{ $voter->polling_center_name ?? '-' }}</td>
                                <td>{{ $voter->ward_number ?? '-' }}</td>
                                <td>{{ $voter->voter_area_number ?? '-' }}</td>
                                <td>{{ $voter->voter_serial_number ?? '-' }}</td>
                                <td>
                                    @if($voter->date_of_birth)
                                        @php
                                            $date = $voter->date_of_birth instanceof \Carbon\Carbon 
                                                ? $voter->date_of_birth 
                                                : \Carbon\Carbon::parse($voter->date_of_birth);
                                        @endphp
                                        {{ $date->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
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
                                <td colspan="13" class="text-center">No voters found. <a href="{{ route('admin.voters.create') }}">Add one</a></td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="dataTables_info">
                        Showing {{ $voters->firstItem() ?? 0 }} to {{ $voters->lastItem() ?? 0 }} of {{ $voters->total() }} entries
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end">
                        {{ $voters->links() }}
                    </div>
                </div>
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
                                <li>Fill in the 3 common fields below - these will apply to ALL voters in the CSV</li>
                                <li>Required columns in CSV: Name, Voter Number</li>
                                <li>Optional columns in CSV: Father Name, Mother Name, Occupation, Address, <strong>Voter Serial Number (ভোটার সিরিয়াল নম্বর)</strong>, Date of Birth</li>
                                <li><strong>Note:</strong> ভোট কেন্দ্র, ওয়ার্ড নম্বর, and ভোটার এলাকার নম্বর are NOT in CSV - enter them below</li>
                                <li><strong>Important:</strong> If your voter number or voter serial number starts with 0 or has leading zeros, prefix it with a single quote (') in Excel to preserve the zeros. Example: '001234 or '261000000000</li>
                                <li>Date format: YYYY-MM-DD, DD/MM/YYYY, M/D/YYYY, or DD-MM-YYYY (e.g., 1990-01-15, 15/01/1990, 1/15/1990)</li>
                                <li>Voter numbers must be unique</li>
                                <li>Maximum file size: 10MB</li>
                            </ul>
                        </div>
                        
                        <div class="form-group">
                            <label for="polling_center_name">ভোট কেন্দ্র <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('polling_center_name') is-invalid @enderror" 
                                   id="polling_center_name" name="polling_center_name" 
                                   value="{{ old('polling_center_name') }}" required>
                            @error('polling_center_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">This value will be applied to all voters in the CSV</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="ward_number">ওয়ার্ড নম্বর <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ward_number') is-invalid @enderror" 
                                   id="ward_number" name="ward_number" 
                                   value="{{ old('ward_number') }}" required>
                            @error('ward_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">This value will be applied to all voters in the CSV</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="voter_area_number">ভোটার এলাকার নম্বর <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('voter_area_number') is-invalid @enderror" 
                                   id="voter_area_number" name="voter_area_number" 
                                   value="{{ old('voter_area_number') }}" required>
                            @error('voter_area_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">This value will be applied to all voters in the CSV</small>
                        </div>
                        
                        <hr>
                        
                        <div class="form-group">
                            <label for="csv_file">Select CSV File <span class="text-danger">*</span></label>
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

@section('css')
<style>
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.875rem;
        }
        
        .table th,
        .table td {
            padding: 0.5rem;
            white-space: nowrap;
        }
        
        .table th:first-child,
        .table td:first-child {
            position: sticky;
            left: 0;
            background: #fff;
            z-index: 1;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .card-header h3 {
            font-size: 1.1rem;
        }
        
        .content-header h1 {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .table {
            font-size: 0.75rem;
        }
        
        .table th,
        .table td {
            padding: 0.35rem;
        }
        
        .btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }
    }
</style>
@stop
