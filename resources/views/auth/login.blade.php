<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('Login') }} - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full bg-gray-50 font-sans antialiased text-gray-900">
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="{{ url('/') }}"
                class="flex justify-center text-3xl font-bold text-indigo-600 tracking-tight">VTTLib</a>
            <h2 class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                {{ __('Sign in to your account') }}
            </h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="bg-white px-6 py-12 shadow sm:rounded-xl sm:px-12 border border-gray-100">
                <form class="space-y-6" action="{{ route('client.login.store') }}" method="POST">
                    @csrf

                    <div>
                        <label for="email"
                            class="block text-sm font-medium leading-6 text-gray-900">{{ __('Email address') }}</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password"
                                class="block text-sm font-medium leading-6 text-gray-900">{{ __('Password') }}</label>
                        </div>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required
                                class="block w-full rounded-lg border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600">
                            <label for="remember-me"
                                class="ml-3 block text-sm leading-6 text-gray-700">{{ __('Remember me') }}</label>
                        </div>

                        <div class="text-sm leading-6">
                            <a href="#"
                                class="font-semibold text-indigo-600 hover:text-indigo-500">{{ __('Forgot password?') }}</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition duration-150">
                            {{ __('Sign in') }}
                        </button>
                    </div>
                </form>

                <div class="mt-10">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm font-medium leading-6">
                            <span class="bg-white px-6 text-gray-900">{{ __('Or continue with') }}</span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('lang.switch', 'vi') }}"
                                class="text-xs font-bold {{ app()->getLocale() == 'vi' ? 'text-indigo-600' : 'text-gray-400' }}">VI</a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('lang.switch', 'en') }}"
                                class="text-xs font-bold {{ app()->getLocale() == 'en' ? 'text-indigo-600' : 'text-gray-400' }}">EN</a>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-10 text-center text-sm text-gray-500">
                {{ __('Not a member?') }}
                <a href="#"
                    class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">{{ __('Start a 14-day free trial') }}</a>
            </p>
        </div>
    </div>
</body>

</html>