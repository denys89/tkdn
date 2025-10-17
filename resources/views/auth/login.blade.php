@extends('layouts.auth')

@section('content')
<div class="text-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back</h2>
    <p class="text-gray-600 dark:text-gray-400 mt-2">Sign in to your account</p>
</div>

<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf
    
    <div>
        <label for="email" class="form-label">Email Address</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                </svg>
            </div>
            <input id="email" type="email" class="form-input pl-10 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
        </div>
        @error('email')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>
    
    <div>
        <label for="password" class="form-label">Password</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <input id="password" type="password" class="form-input pl-10 @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror" name="password" required placeholder="Enter your password">
        </div>
        @error('password')
            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Remember me</label>
        </div>
        
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-200">
                Forgot password?
            </a>
        @endif
    </div>
    
    <div>
        <button type="submit" class="btn btn-primary w-full py-3 text-base font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
            </svg>
            Sign in
        </button>
    </div>
</form>

<div class="mt-6 text-center">
    <p class="text-sm text-gray-600 dark:text-gray-400">
        Don't have an account? 
        <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors duration-200">
            Sign up
        </a>
    </p>
</div>
@endsection
