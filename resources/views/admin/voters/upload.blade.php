@extends('adminlte::page')

@section('title', 'Bulk Upload Voters')

@section('content_header')
    <h1><i class="fas fa-upload"></i> Bulk Upload Voters from CSV</h1>
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
            <div class="mb-3">
                <a href="{{ route('admin.voters.download-template') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-download"></i> Download CSV Template
                </a>
                <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-list"></i> Back to Voter List
                </a>
            </div>

            <form action="{{ route('admin.voters.import-csv') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Instructions:</strong>
                    <ul class="mb-0 mt-2">
                        <li>Download the CSV template first to see the correct format</li>
                        <li>Fill in the 3 common fields below - these will apply to ALL voters in the CSV</li>
                        <li>Required columns in CSV: Name, Voter Number</li>
                        <li>Optional columns: Father Name, Mother Name, Occupation, Address, Voter Serial Number (ভোটার সিরিয়াল নম্বর), Date of Birth</li>
                        <li>ভোট কেন্দ্র, ওয়ার্ড নম্বর, and ভোটার এলাকার নম্বর are entered below (not in CSV)</li>
                        <li>If voter number or serial starts with 0, prefix with a single quote (') in Excel. Example: '001234</li>
                        <li>Date format: YYYY-MM-DD, DD/MM/YYYY, or DD-MM-YYYY</li>
                        <li>Name + Voter Number together must be unique</li>
                        <li>Maximum file size: 50MB. Supports 3k–4k+ rows with chunk processing.</li>
                    </ul>
                </div>

                <div class="form-group">
                    <label for="polling_center_name">ভোট কেন্দ্র (Polling Center) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('polling_center_name') is-invalid @enderror"
                           id="polling_center_name" name="polling_center_name"
                           value="{{ old('polling_center_name') }}" required>
                    @error('polling_center_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Applied to all voters in the CSV</small>
                </div>

                <div class="form-group">
                    <label for="ward_number">ওয়ার্ড নম্বর (Ward Number) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('ward_number') is-invalid @enderror"
                           id="ward_number" name="ward_number"
                           value="{{ old('ward_number') }}" required>
                    @error('ward_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Applied to all voters in the CSV</small>
                </div>

                <div class="form-group">
                    <label for="voter_area_number">ভোটার এলাকার নম্বর (Voter Area Number) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('voter_area_number') is-invalid @enderror"
                           id="voter_area_number" name="voter_area_number"
                           value="{{ old('voter_area_number') }}" required>
                    @error('voter_area_number')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Applied to all voters in the CSV</small>
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

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Upload & Import
                </button>
                <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('csv_file').addEventListener('change', function(e) {
            var fileName = e.target.files[0]?.name || 'Choose file...';
            e.target.nextElementSibling.textContent = fileName;
        });
    </script>
@stop
