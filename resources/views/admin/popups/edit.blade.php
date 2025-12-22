@extends('adminlte::page')

@section('title', 'Edit Popup')

@section('content_header')
    <h1><i class="fas fa-edit"></i> Edit Popup</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.popups.update', $popup) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="subtitle">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle" class="form-control @error('subtitle') is-invalid @enderror" 
                           value="{{ old('subtitle', $popup->subtitle) }}" placeholder="ঢাকা–১৩ আসনের সংসদ সদস্য পদপ্রার্থী">
                    @error('subtitle')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Subtitle text that appears above the title (optional)</small>
                </div>

                <div class="form-group">
                    <label for="title">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title', $popup->title) }}" required>
                    @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message">Message <span class="text-danger">*</span></label>
                    <textarea name="message" id="message" rows="3" class="form-control @error('message') is-invalid @enderror" required>{{ old('message', $popup->message) }}</textarea>
                    @error('message')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Person/Candidate Image</label>
                    @if($popup->image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($popup->image) }}" alt="Current image" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                            <p class="text-muted">Current image</p>
                        </div>
                    @endif
                    <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror" 
                           accept="image/*">
                    @error('image')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Upload new image to replace current one (JPEG, PNG, JPG, GIF - Max 2MB)</small>
                </div>

                <div class="form-group">
                    <label for="icon_image">Icon/Symbol Image (PNG)</label>
                    @if($popup->icon_image)
                        <div class="mb-2">
                            <img src="{{ Storage::url($popup->icon_image) }}" alt="Current icon" style="max-width: 200px; max-height: 200px;" class="img-thumbnail">
                            <p class="text-muted">Current icon</p>
                        </div>
                    @endif
                    <input type="file" name="icon_image" id="icon_image" class="form-control-file @error('icon_image') is-invalid @enderror" 
                           accept="image/*">
                    @error('icon_image')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Upload new icon to replace current one (JPEG, PNG, JPG, GIF - Max 2MB)</small>
                </div>

                <div class="form-group">
                    <div class="icheck-primary">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $popup->is_active) ? 'checked' : '' }}>
                        <label for="is_active">Active</label>
                    </div>
                    <small class="form-text text-muted">Only one active popup will be shown on the frontend</small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Popup
                    </button>
                    <a href="{{ route('admin.popups.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

