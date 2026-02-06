@extends('adminlte::page')

@section('title', 'View Voter')

@section('content_header')
    <h1><i class="fas fa-user"></i> View Voter</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>নাম (Name)</label>
                        <p class="form-control-plaintext">{{ $voter->name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ভোটার নম্বর (Voter Number)</label>
                        <p class="form-control-plaintext"><strong>{{ $voter->voter_number }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>পিতা (Father's Name)</label>
                        <p class="form-control-plaintext">{{ $voter->father_name ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>মাতা (Mother's Name)</label>
                        <p class="form-control-plaintext">{{ $voter->mother_name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>পেশা (Occupation)</label>
                        <p class="form-control-plaintext">{{ $voter->occupation ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>জন্ম তারিখ (Date of Birth)</label>
                        <p class="form-control-plaintext">
                            @if($voter->date_of_birth)
                                @php
                                    try {
                                        $date = $voter->date_of_birth instanceof \Carbon\Carbon 
                                            ? $voter->date_of_birth 
                                            : \Carbon\Carbon::parse($voter->date_of_birth);
                                        echo $date->format('d/m/Y');
                                    } catch (\Exception $e) {
                                        echo '-';
                                    }
                                @endphp
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>ঠিকানা (Address)</label>
                <p class="form-control-plaintext">{{ $voter->address ?? '-' }}</p>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>ভোট কেন্দ্রের নাম (Polling Center Name)</label>
                        <p class="form-control-plaintext">{{ $voter->polling_center_name ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>ওয়ার্ড নম্বর (Ward Number)</label>
                        <p class="form-control-plaintext">{{ $voter->ward_number ?? '-' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>ভোটার এলাকার নম্বর (Voter Area Number)</label>
                        <p class="form-control-plaintext">{{ $voter->voter_area_number ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>ভোটার সিরিয়াল নম্বর (Voter Serial Number)</label>
                        <p class="form-control-plaintext">{{ $voter->voter_serial_number ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <a href="{{ route('admin.voters.edit', $voter) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('admin.voters.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
@stop
