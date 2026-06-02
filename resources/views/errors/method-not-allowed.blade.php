<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Method Not Allowed') }} - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white shadow-lg rounded-lg p-8 text-center">
        <div class="mb-6">
            <i class="fas fa-ban text-6xl text-red-500"></i>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-4">
            {{ __('Method Not Allowed') }}
        </h1>
        
        <p class="text-gray-600 mb-6">
            {{ __('The HTTP method you used is not supported for this route.') }}
        </p>
        
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 text-left">
            <p class="text-sm text-red-700">
                <strong>{{ __('Route:') }}</strong> {{ request()->path() }}
            </p>
            <p class="text-sm text-red-700 mt-2">
                <strong>{{ __('Method Used:') }}</strong> {{ request()->method() }}
            </p>
        </div>
        
        <div class="space-y-3">
            <a href="{{ url()->previous() }}" class="inline-block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                {{ __('Go Back') }}
            </a>
            
            <a href="{{ url('/') }}" class="inline-block w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                <i class="fas fa-home mr-2"></i>
                {{ __('Go to Homepage') }}
            </a>
        </div>
    </div>
</body>
</html>
