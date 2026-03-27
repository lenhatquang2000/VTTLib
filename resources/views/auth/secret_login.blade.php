<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Login') }} - VTTLib</title>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 25%, #0f3460 50%, #533483 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Darkling theme effects */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(139, 92, 246, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(236, 72, 153, 0.2) 0%, transparent 50%);
            animation: floatingGradient 20s ease-in-out infinite;
        }
        
        @keyframes floatingGradient {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(-20px, -20px) rotate(120deg); }
            66% { transform: translate(20px, -10px) rotate(240deg); }
        }
        
        /* Animated particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }
        
        .login-container {
            width: 100%;
            max-width: 1200px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }
        
        .login-card {
            background: rgba(30, 30, 46, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 24px;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(139, 92, 246, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 480px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.5), transparent);
            animation: shimmer 3s ease-in-out infinite;
        }
        
        @keyframes shimmer {
            0%, 100% { transform: translateX(-100%); }
            50% { transform: translateX(100%); }
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 
                0 30px 60px -12px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(139, 92, 246, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
            position: relative;
        }
        
        .logo {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: transparent;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
            text-shadow: 0 0 30px rgba(139, 92, 246, 0.5);
        }
        
        .login-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #e2e8f0;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            font-size: 0.875rem;
            color: #94a3b8;
            text-align: center;
            margin-top: 0.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
            pointer-events: none;
            transition: color 0.3s ease;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            background: rgba(15, 23, 42, 0.8);
            border: 2px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            color: #e2e8f0;
            transition: all 0.3s ease;
            outline: none;
        }
        
        .form-input:focus {
            border-color: #8b5cf6;
            background: rgba(15, 23, 42, 0.95);
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }
        
        .form-input:focus + .input-icon {
            color: #8b5cf6;
        }
        
        .form-input::placeholder {
            color: #64748b;
        }
        
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 50%, #ec4899 100%);
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }
        
        .back-link:hover {
            color: #8b5cf6;
        }
        
        .back-link i {
            margin-right: 0.5rem;
        }
        
        .security-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(139, 92, 246, 0.1);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            color: #cbd5e1;
            border: 1px solid rgba(139, 92, 246, 0.2);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .language-switcher {
            position: absolute;
            top: 1rem;
            left: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        
        .lang-btn {
            padding: 0.375rem 0.75rem;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 6px;
            color: #cbd5e1;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .lang-btn:hover {
            background: rgba(139, 92, 246, 0.2);
            color: #ffffff;
        }
        
        .lang-btn.active {
            background: #8b5cf6;
            color: #ffffff;
            border-color: #8b5cf6;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                padding: 1rem;
            }
            
            .login-card {
                padding: 2rem;
                border-radius: 16px;
            }
            
            .logo {
                font-size: 2rem;
            }
            
            .login-title {
                font-size: 1.25rem;
            }
        }
        
        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .login-card {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Error states */
        .error-message {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: 0.75rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="h-full">
    <!-- Animated Particles -->
    <div class="particles">
        <div class="particle" style="width: 4px; height: 4px; left: 10%; animation-delay: 0s; animation-duration: 15s;"></div>
        <div class="particle" style="width: 3px; height: 3px; left: 20%; animation-delay: 2s; animation-duration: 12s;"></div>
        <div class="particle" style="width: 5px; height: 5px; left: 30%; animation-delay: 4s; animation-duration: 18s;"></div>
        <div class="particle" style="width: 2px; height: 2px; left: 40%; animation-delay: 1s; animation-duration: 14s;"></div>
        <div class="particle" style="width: 6px; height: 6px; left: 50%; animation-delay: 3s; animation-duration: 16s;"></div>
        <div class="particle" style="width: 3px; height: 3px; left: 60%; animation-delay: 5s; animation-duration: 13s;"></div>
        <div class="particle" style="width: 4px; height: 4px; left: 70%; animation-delay: 2.5s; animation-duration: 15s;"></div>
        <div class="particle" style="width: 5px; height: 5px; left: 80%; animation-delay: 4.5s; animation-duration: 17s;"></div>
        <div class="particle" style="width: 3px; height: 3px; left: 90%; animation-delay: 1.5s; animation-duration: 14s;"></div>
    </div>
    
    <div class="login-container">
        <div class="login-card">
            <!-- Language Switcher -->
            <div class="language-switcher" x-data="{ open: false }">
                <button @click="open = !open" class="lang-btn" :class="{ 'active': app()->getLocale() == 'vi' }">
                    {{ app()->getLocale() == 'vi' ? 'VI' : 'EN' }}
                </button>
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="absolute top-full left-0 mt-2 z-50">
                    <a href="{{ route('lang.switch', 'vi') }}" class="lang-btn {{ app()->getLocale() == 'vi' ? 'active' : '' }}">VI</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                </div>
            </div>
            
            <!-- Security Badge -->
            <div class="security-badge">
                <i class="fas fa-shield-alt"></i>
                {{ __('Secured by VTTLib') }}
            </div>
            
            <!-- Login Header -->
            <div class="login-header">
                <div class="logo">VTTLib</div>
                <h1 class="login-title">{{ __('Hệ thống Quản lý') }}</h1>
                <p class="login-subtitle">{{ __('Nhập thông tin định danh để tiếp tục.') }}</p>
            </div>
            
            <!-- Login Form -->
            <form action="{{ route('agent.login.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="error-message">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
                
                <!-- Username Field -->
                <div class="form-group">
                    <label for="username" class="form-label">{{ __('Username') }}</label>
                    <div class="input-wrapper">
                        <input 
                            id="username" 
                            name="username" 
                            type="text" 
                            value="{{ old('username') }}" 
                            required
                            class="form-input"
                            placeholder="{{ __('Tên tài khoản') }}"
                            autocomplete="username"
                        >
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>
                
                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Mật khẩu') }}</label>
                    <div class="input-wrapper">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required
                            class="form-input"
                            placeholder="••••••••"
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i>
                    {{ __('Đăng nhập hệ thống') }}
                </button>
            </form>
            
            <!-- Back to Home -->
            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ url('/') }}" class="back-link">
                    <i class="fas fa-arrow-left"></i>
                    {{ __('Back to Home') }}
                </a>
            </div>
        </div>
    </div>
</body>
</html>