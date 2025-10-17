@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Settings</h1>
            <p class="text-gray-600 dark:text-gray-400">Manage your account settings and preferences</p>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account Settings</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" 
                               class="form-input @error('name') border-red-500 @enderror" 
                               placeholder="Enter your name" required>
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                               class="form-input @error('email') border-red-500 @enderror" 
                               placeholder="Enter your email" required>
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Change Password</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Password -->
                        <div class="form-group">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" id="current_password" name="current_password" 
                                   class="form-input @error('current_password') border-red-500 @enderror" 
                                   placeholder="Enter current password">
                            @error('current_password')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="form-group">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" id="password" name="password" 
                                   class="form-input @error('password') border-red-500 @enderror" 
                                   placeholder="Enter new password">
                            @error('password')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group md:col-span-2">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="form-input" 
                                   placeholder="Confirm new password">
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="btn btn-primary flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="card border-red-200 dark:border-red-800">
        <div class="card-header border-red-200 dark:border-red-800">
            <h3 class="text-lg font-semibold text-red-700 dark:text-red-400">Danger Zone</h3>
        </div>
        <div class="card-body">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h4 class="text-base font-medium text-gray-900 dark:text-white">Delete Account</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Permanently delete your account and all associated data. This action cannot be undone.
                    </p>
                </div>
                <button type="button" class="btn btn-danger mt-4 sm:mt-0" onclick="confirmDelete()">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone.')) {
        // Add delete account logic here
        console.log('Delete account confirmed');
    }
}
</script>
@endsection 