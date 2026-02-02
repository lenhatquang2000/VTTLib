@extends('layouts.root')

@section('content')
    <div class="space-y-8">
        @if(session('success'))
            <div class="bg-green-900/20 border border-green-900 text-green-500 p-4 text-xs font-mono">
                [OK] {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-900/20 border border-red-900 text-red-500 p-4 text-xs font-mono">
                [ERROR] {{ session('error') }}
            </div>
        @endif

        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-red-900/5 p-4 border border-red-900/20 rounded gap-4">
            <h2 class="text-sm font-bold uppercase tracking-widest">{{ __('Edit_User') }}</h2>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('root.users.index') }}" 
                    class="bg-transparent border border-red-900 text-red-500 px-4 py-2 text-xs font-bold uppercase hover:bg-red-900/10 transition">
                    {{ __('Back_to_List') }}
                </a>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-black/50 border border-red-900/30 rounded-lg p-6">
            <form action="{{ route('root.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div class="space-y-2">
                        <label class="text-xs text-red-500 font-bold uppercase tracking-widest">{{ __('Full_Name') }}</label>
                        <input type="text" name="name" value="{{ $user->name }}" required
                            class="w-full bg-black border border-red-900/30 p-3 text-xs text-red-500 focus:border-red-500 outline-none">
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label class="text-xs text-red-500 font-bold uppercase tracking-widest">{{ __('Email_Address') }}</label>
                        <input type="email" name="email" value="{{ $user->email }}" required
                            class="w-full bg-black border border-red-900/30 p-3 text-xs text-red-500 focus:border-red-500 outline-none">
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-xs text-red-500 font-bold uppercase tracking-widest">{{ __('New_Password') }}</label>
                        <input type="password" name="password" 
                            placeholder="{{ __('Leave_blank_to_keep_current') }}"
                            class="w-full bg-black border border-red-900/30 p-3 text-xs text-red-500 focus:border-red-500 outline-none">
                        <p class="text-xs text-red-900">{{ __('Optional_Min_6_characters') }}</p>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="space-y-2">
                        <label class="text-xs text-red-500 font-bold uppercase tracking-widest">{{ __('Confirm_Password') }}</label>
                        <input type="password" name="password_confirmation" 
                            placeholder="{{ __('Confirm_new_password') }}"
                            class="w-full bg-black border border-red-900/30 p-3 text-xs text-red-500 focus:border-red-500 outline-none">
                    </div>
                </div>

                <!-- User Info -->
                <div class="mt-6 p-4 bg-red-900/10 border border-red-900/20 rounded">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                        <div>
                            <span class="text-red-900">{{ __('User_ID') }}:</span>
                            <span class="text-red-500 font-mono ml-2">#{{ $user->id }}</span>
                        </div>
                        <div>
                            <span class="text-red-900">{{ __('Created') }}:</span>
                            <span class="text-red-500 ml-2">{{ $user->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div>
                            <span class="text-red-900">{{ __('Roles') }}:</span>
                            <span class="text-red-500 ml-2">{{ $user->roles->pluck('name')->implode(', ') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex flex-col md:flex-row gap-4">
                    <button type="submit" 
                        class="bg-red-900 text-black px-6 py-3 text-xs font-bold uppercase hover:bg-red-600 transition">
                        {{ __('Update_User') }}
                    </button>
                    <a href="{{ route('root.users.index') }}" 
                        class="bg-transparent border border-red-900 text-red-500 px-6 py-3 text-xs font-bold uppercase hover:bg-red-900/10 transition text-center">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
