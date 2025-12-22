@extends('adminlte::page')

@section('title', 'Add New Voter')

@section('content_header')
    <h1><i class="fas fa-user-plus"></i> Add New Voter</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.voters.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">নাম (Name) <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="voter_number">ভোটার নম্বর (Voter Number) <span class="text-danger">*</span></label>
                            <input type="text" name="voter_number" id="voter_number" 
                                   class="form-control @error('voter_number') is-invalid @enderror" 
                                   value="{{ old('voter_number') }}" required>
                            @error('voter_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="father_name">পিতা (Father's Name)</label>
                            <input type="text" name="father_name" id="father_name" 
                                   class="form-control @error('father_name') is-invalid @enderror" 
                                   value="{{ old('father_name') }}">
                            @error('father_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="mother_name">মাতা (Mother's Name)</label>
                            <input type="text" name="mother_name" id="mother_name" 
                                   class="form-control @error('mother_name') is-invalid @enderror" 
                                   value="{{ old('mother_name') }}">
                            @error('mother_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="occupation">পেশা (Occupation)</label>
                            <input type="text" name="occupation" id="occupation" 
                                   class="form-control @error('occupation') is-invalid @enderror" 
                                   value="{{ old('occupation') }}">
                            @error('occupation')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth">জন্ম তারিখ (Date of Birth)</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" 
                                   class="form-control @error('date_of_birth') is-invalid @enderror" 
                                   value="{{ old('date_of_birth') }}">
                            @error('date_of_birth')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">ঠিকানা (Address)</label>
                    <textarea name="address" id="address" rows="3" 
                              class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="polling_center_name">ভোট কেন্দ্রের নাম (Polling Center Name)</label>
                            <input type="text" name="polling_center_name" id="polling_center_name" 
                                   class="form-control @error('polling_center_name') is-invalid @enderror" 
                                   value="{{ old('polling_center_name') }}">
                            @error('polling_center_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ward_number">ওয়ার্ড নম্বর (Ward Number)</label>
                            <input type="text" name="ward_number" id="ward_number" 
                                   class="form-control @error('ward_number') is-invalid @enderror" 
                                   value="{{ old('ward_number') }}">
                            @error('ward_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Voter
                    </button>
                    <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
