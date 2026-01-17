@extends('adminlte::page')

@section('title', 'Popups Management')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-window-restore"></i> Popups Management</h1>
        <a href="{{ route('admin.popups.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Popup
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
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Format</th>
                        <th>Image</th>
                        <th>Icon</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($popups as $popup)
                        <tr>
                            <td>{{ $popup->id }}</td>
                            <td>
                                <span class="badge badge-{{ $popup->format == '1' ? 'primary' : 'info' }}">
                                    Format {{ $popup->format ?? '1' }}
                                </span>
                            </td>
                            <td>
                                @if($popup->image)
                                    <img src="{{ asset($popup->image) }}" alt="Image" style="max-width: 50px; max-height: 50px;">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>
                                @if($popup->format == '1')
                                    @if($popup->icon_image)
                                        <img src="{{ asset($popup->icon_image) }}" alt="Icon" style="max-width: 50px; max-height: 50px;">
                                    @else
                                        <span class="text-muted">No icon</span>
                                    @endif
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($popup->title, 30) }}</td>
                            <td>
                                @if($popup->format == '1')
                                    {{ Str::limit($popup->message, 30) }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($popup->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.popups.edit', $popup) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.popups.destroy', $popup) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No popups found. <a href="{{ route('admin.popups.create') }}">Create one</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

