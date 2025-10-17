@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center">
        <svg class="w-7 h-7 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 14v.01M12 10v.01M16 10v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        Support
    </h1>
    <p class="text-gray-600 dark:text-gray-400">Get help and support for your TKDN services</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Email Support Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col justify-between">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 01-8 0m8 0V8a4 4 0 00-8 0v4m8 0a4 4 0 01-8 0" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Email Support</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Send us an email for detailed assistance</p>
            </div>
        </div>
        <div class="mb-4">
            <span class="block text-sm text-gray-500 dark:text-gray-400 font-medium">Email:</span>
            <span class="block text-base font-semibold text-gray-900 dark:text-white">support@sena.com</span>
        </div>
        <a href="mailto:support@sena.com" class="btn btn-primary flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 01-8 0m8 0V8a4 4 0 00-8 0v4m8 0a4 4 0 01-8 0" />
            </svg>
            Send Email
        </a>
    </div>
    <!-- WhatsApp Support Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col justify-between">
        <div class="flex items-center mb-4">
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center mr-4">
                <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 17.387A9 9 0 1121 12c0 1.657-.402 3.22-1.138 4.613l-1.5 3.75a1 1 0 01-1.324.553l-3.75-1.5z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">WhatsApp Support</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Chat with us directly on WhatsApp</p>
            </div>
        </div>
        <div class="mb-4">
            <span class="block text-sm text-gray-500 dark:text-gray-400 font-medium">WhatsApp:</span>
            <span class="block text-base font-semibold text-gray-900 dark:text-white">+62 821-0000-7777</span>
        </div>
        <a href="https://wa.me/6282100007777" target="_blank" class="btn flex items-center justify-center bg-green-600 hover:bg-green-700 text-white">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 17.387A9 9 0 1121 12c0 1.657-.402 3.22-1.138 4.613l-1.5 3.75a1 1 0 01-1.324.553l-3.75-1.5z" />
            </svg>
            Chat on WhatsApp
        </a>
    </div>
</div>
@endsection 