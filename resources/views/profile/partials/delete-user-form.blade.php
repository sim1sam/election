<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i> 
    <strong>Warning:</strong> Once your account is deleted, all of its resources and data will be permanently deleted. 
    Before deleting your account, please download any data or information that you wish to retain.
</div>

<button type="button" 
        class="btn btn-danger" 
        data-toggle="modal" 
        data-target="#deleteAccountModal">
    <i class="fas fa-trash"></i> Delete Account
</button>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Delete Account
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                
                <div class="modal-body">
                    <p class="mb-3">
                        <strong>Are you sure you want to delete your account?</strong>
                    </p>
                    <p class="text-muted">
                        Once your account is deleted, all of its resources and data will be permanently deleted. 
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>
                    
                    <div class="form-group mt-3">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               placeholder="Enter your password" 
                               required>
                        @error('password', 'userDeletion')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
