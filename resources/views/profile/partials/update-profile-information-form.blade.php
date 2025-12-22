<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="form-group">
        <label for="name">Name <span class="text-danger">*</span></label>
        <input type="text" 
               name="name" 
               id="name" 
               class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name', $user->name) }}" 
               required 
               autofocus>
        @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email <span class="text-danger">*</span></label>
        <input type="email" 
               name="email" 
               id="email" 
               class="form-control @error('email') is-invalid @enderror" 
               value="{{ old('email', $user->email) }}" 
               required>
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <small class="form-text text-muted">
                Your email address is unverified.
                <button form="send-verification" type="submit" class="btn btn-link p-0 text-primary">
                    Click here to re-send the verification email.
                </button>
            </small>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success mt-2">
                    <i class="fas fa-check-circle"></i> A new verification link has been sent to your email address.
                </div>
            @endif
        @endif
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </div>
</form>
