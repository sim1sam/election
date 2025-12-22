@extends('adminlte::page')

@section('title', 'Home Page Settings')

@section('content_header')
    <h1><i class="fas fa-cog"></i> Home Page Settings</h1>
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
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-edit"></i> Edit Home Page Content</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.home-page-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-3">Page Header</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="page_title">Page Title <span class="text-danger">*</span></label>
                            <input type="text" name="page_title" id="page_title" 
                                   class="form-control @error('page_title') is-invalid @enderror" 
                                   value="{{ old('page_title', $settings->page_title) }}" required>
                            @error('page_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-3">Countdown Timer Section</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="countdown_title">Countdown Section Title <span class="text-danger">*</span></label>
                            <input type="text" name="countdown_title" id="countdown_title" 
                                   class="form-control @error('countdown_title') is-invalid @enderror" 
                                   value="{{ old('countdown_title', $settings->countdown_title) }}" required>
                            @error('countdown_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="countdown_message">Countdown Message <span class="text-danger">*</span></label>
                            <input type="text" name="countdown_message" id="countdown_message" 
                                   class="form-control @error('countdown_message') is-invalid @enderror" 
                                   value="{{ old('countdown_message', $settings->countdown_message) }}" required>
                            @error('countdown_message')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="countdown_target_date">Target Date <span class="text-danger">*</span></label>
                            <input type="date" name="countdown_target_date" id="countdown_target_date" 
                                   class="form-control @error('countdown_target_date') is-invalid @enderror" 
                                   value="{{ old('countdown_target_date', $settings->countdown_target_date ? $settings->countdown_target_date->format('Y-m-d') : '') }}" required>
                            @error('countdown_target_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="countdown_target_time">Target Time <span class="text-danger">*</span></label>
                            <input type="time" name="countdown_target_time" id="countdown_target_time" 
                                   class="form-control @error('countdown_target_time') is-invalid @enderror" 
                                   value="{{ old('countdown_target_time', $settings->countdown_target_date ? $settings->countdown_target_date->format('H:i') : '08:00') }}" required>
                            @error('countdown_target_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">The countdown timer will count down to this date and time</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-3">Waiting Message Section</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="waiting_title">Waiting Title <span class="text-danger">*</span></label>
                            <input type="text" name="waiting_title" id="waiting_title" 
                                   class="form-control @error('waiting_title') is-invalid @enderror" 
                                   value="{{ old('waiting_title', $settings->waiting_title) }}" required>
                            @error('waiting_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="waiting_message_1">Waiting Message 1 <span class="text-danger">*</span></label>
                            <textarea name="waiting_message_1" id="waiting_message_1" rows="2" 
                                      class="form-control @error('waiting_message_1') is-invalid @enderror" required>{{ old('waiting_message_1', $settings->waiting_message_1) }}</textarea>
                            @error('waiting_message_1')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="waiting_message_2">Waiting Message 2 <span class="text-danger">*</span></label>
                            <textarea name="waiting_message_2" id="waiting_message_2" rows="2" 
                                      class="form-control @error('waiting_message_2') is-invalid @enderror" required>{{ old('waiting_message_2', $settings->waiting_message_2) }}</textarea>
                            @error('waiting_message_2')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-3"><i class="fas fa-info-circle"></i> Election Information Section</h4>
                        <p class="text-muted mb-3">This section displays election area information on the voter search page</p>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="election_info_title">Election Info Section Title <span class="text-danger">*</span></label>
                            <input type="text" name="election_info_title" id="election_info_title" 
                                   class="form-control @error('election_info_title') is-invalid @enderror" 
                                   value="{{ old('election_info_title', $settings->election_info_title) }}" 
                                   placeholder="নির্বাচনী এলাকা তথ্য" required>
                            @error('election_info_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Title for the election information section (e.g., নির্বাচনী এলাকা তথ্য)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="area_name">Area Name <span class="text-danger">*</span></label>
                            <input type="text" name="area_name" id="area_name" 
                                   class="form-control @error('area_name') is-invalid @enderror" 
                                   value="{{ old('area_name', $settings->area_name) }}" 
                                   placeholder="ঢাকা-১৩" required>
                            @error('area_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Election area name (e.g., ঢাকা-১৩)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="election_center">Election Center <span class="text-danger">*</span></label>
                            <input type="text" name="election_center" id="election_center" 
                                   class="form-control @error('election_center') is-invalid @enderror" 
                                   value="{{ old('election_center', $settings->election_center) }}" 
                                   placeholder="50" required>
                            @error('election_center')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Number of election centers (e.g., 50)</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="total_voters">Total Voters <span class="text-danger">*</span></label>
                            <input type="text" name="total_voters" id="total_voters" 
                                   class="form-control @error('total_voters') is-invalid @enderror" 
                                   value="{{ old('total_voters', $settings->total_voters) }}" 
                                   placeholder="400000" required>
                            @error('total_voters')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Total number of voters (e.g., 400000). Note: The actual count from database will be shown on the search page.</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-3">Voters Section</h4>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="voters_section_title">Voters Section Title <span class="text-danger">*</span></label>
                            <input type="text" name="voters_section_title" id="voters_section_title" 
                                   class="form-control @error('voters_section_title') is-invalid @enderror" 
                                   value="{{ old('voters_section_title', $settings->voters_section_title) }}" required>
                            @error('voters_section_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="total_voters_label">Total Voters Label <span class="text-danger">*</span></label>
                            <input type="text" name="total_voters_label" id="total_voters_label" 
                                   class="form-control @error('total_voters_label') is-invalid @enderror" 
                                   value="{{ old('total_voters_label', $settings->total_voters_label) }}" required>
                            @error('total_voters_label')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h4 class="mb-3">Post-Countdown Search Page Settings</h4>
                        <p class="text-muted">These fields will be shown when the countdown timer ends</p>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="post_countdown_title">Post-Countdown Page Title</label>
                            <input type="text" name="post_countdown_title" id="post_countdown_title" 
                                   class="form-control @error('post_countdown_title') is-invalid @enderror" 
                                   value="{{ old('post_countdown_title', $settings->post_countdown_title) }}" 
                                   placeholder="ভোটার তথ্য খুঁজুন">
                            @error('post_countdown_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Title shown on the search page after countdown ends</small>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="post_countdown_subtitle">Post-Countdown Page Subtitle</label>
                            <input type="text" name="post_countdown_subtitle" id="post_countdown_subtitle" 
                                   class="form-control @error('post_countdown_subtitle') is-invalid @enderror" 
                                   value="{{ old('post_countdown_subtitle', $settings->post_countdown_subtitle) }}" 
                                   placeholder="ওয়ার্ড নম্বর এবং জন্ম তারিখ দিয়ে ভোটার তথ্য খুঁজুন">
                            @error('post_countdown_subtitle')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Subtitle shown below the title</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Settings
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
