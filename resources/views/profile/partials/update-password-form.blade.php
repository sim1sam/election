<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="form-group">
        <label for="update_password_current_password">Current Password <span class="text-danger">*</span></label>
        <input type="password" 
               name="current_password" 
               id="update_password_current_password" 
               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
               autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="update_password_password">New Password <span class="text-danger">*</span></label>
        <input type="password" 
               name="password" 
               id="update_password_password" 
               class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
               autocomplete="new-password">
        @error('password', 'updatePassword')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        <small class="form-text text-muted">
            Ensure your account is using a long, random password to stay secure.
        </small>
    </div>

    <div class="form-group">
        <label for="update_password_password_confirmation">Confirm Password <span class="text-danger">*</span></label>
        <input type="password" 
               name="password_confirmation" 
               id="update_password_password_confirmation" 
               class="form-control" 
               autocomplete="new-password">
        @error('password_confirmation', 'updatePassword')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-key"></i> Update Password
        </button>
    </div>
</form>
