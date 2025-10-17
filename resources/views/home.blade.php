@extends('layouts.app')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center bg-gradient-to-br from-primary-100 via-white to-primary-200 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12">
    <div class="glass max-w-xl w-full mx-auto rounded-2xl shadow-2xl p-8 flex flex-col items-center">
        <div class="flex items-center space-x-4 mb-6">
            <div class="bg-primary-600 text-white rounded-full p-3 shadow-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104.896-2 2-2s2 .896 2 2-.896 2-2 2-2-.896-2-2zm0 0V7m0 4v4m0 0c0 1.104-.896 2-2 2s-2-.896-2-2 .896-2 2-2 2 .896 2 2z" />
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-1 text-shadow">Welcome to <span class="text-primary-600 dark:text-primary-400">Dashboard</span></h2>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success w-full text-center text-base font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-lg py-2 mb-4 shadow">
                {{ session('status') }}
            </div>
        @endif
        <div class="mt-4 text-center">
            <p class="text-base text-gray-700 dark:text-gray-400 font-medium">Explore your projects, manage your team, and get insights right from your dashboard.</p>
        </div>
    </div>
</div>
@endsection
