@extends('layouts.client')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2"
                    fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100" />
                </svg>

                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Welcome to</span>
                            <span class="block text-indigo-600 xl:inline">VTTLib</span>
                        </h1>
                        <p
                            class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Discover a world of knowledge with our vast collection of books. Borrow, read, and grow your
                            mind with VTTLib.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="#"
                                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg">
                                    Browse Books
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#"
                                    class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg">
                                    Learn more
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full"
                src="https://images.unsplash.com/photo-1507842217121-9d59630c13e4?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1950&q=80"
                alt="">
        </div>
    </div>

    <!-- Featured Books Section -->
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Library</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Featured Books
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Check out the latest additions to our collection.
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:gap-x-8">
                    <!-- Book 1 -->
                    <div class="group relative bg-white p-4 rounded-xl shadow-sm hover:shadow-md transition">
                        <div
                            class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <!-- Placeholder for Book Cover -->
                                <span class="text-6xl">📖</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="#">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        The Art of Code
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">John Doe</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Available</p>
                        </div>
                    </div>

                    <!-- Book 2 -->
                    <div class="group relative bg-white p-4 rounded-xl shadow-sm hover:shadow-md transition">
                        <div
                            class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <span class="text-6xl">📘</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="#">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Laravel Mastery
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">Jane Smith</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Available</p>
                        </div>
                    </div>

                    <!-- Book 3 -->
                    <div class="group relative bg-white p-4 rounded-xl shadow-sm hover:shadow-md transition">
                        <div
                            class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                            <div class="flex items-center justify-center h-full text-gray-400">
                                <span class="text-6xl">📕</span>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="#">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        Design Patterns
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">Gang of Four</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Checked Out</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection