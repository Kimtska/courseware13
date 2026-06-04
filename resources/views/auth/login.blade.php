<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VirtualArm - Login</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-50 grid-pattern min-h-screen flex items-center justify-center p-4">
    
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-violet-100/40 rounded-full blur-[200px]"></div>
    </div>

    <div class="max-w-xl w-full relative z-10 fade-in-up visible mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-3 mb-6">
                <img src="{{ asset('images/assets/logo.png') }}" alt="SPC College of Criminology" class="h-12 w-auto">
                <div class="flex flex-col">
                    <span class="font-display font-bold text-lg tracking-tight text-black">Virtual<span class="text-violet-700">Arm</span></span>
                    <span class="text-[9px] text-gray-400 tracking-wider uppercase font-medium">SPC College of Criminology</span>
                </div>
            </div>
        </div>

        <!-- Login Form Card -->
        <div class="p-8 md:p-12 rounded-2xl bg-white border border-violet-100 shadow-xl">
            
            <h2 class="font-display font-bold text-3xl text-black mb-2 text-center">Welcome Back</h2>
            <p class="text-gray-400 text-sm text-center mb-8">Admin, instructor, and student accounts all use email and password</p>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-sm"><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-600 text-sm"><i class="fas fa-check-circle mr-2"></i>{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-600 mb-1.5">Email Address / Student ID</label>
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="Enter your email or student ID"
                        inputmode="text"
                        autocomplete="username"
                        class="w-full px-4 py-3 bg-light-50 border border-violet-100 rounded-lg text-black text-sm placeholder-gray-400 focus:outline-none focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(91,33,182,.1)] transition-all @error('email') border-red-500 @enderror"
                        required
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-gray-600 mb-1.5">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 bg-light-50 border border-violet-100 rounded-lg text-black text-sm placeholder-gray-400 focus:outline-none focus:border-violet-400 focus:shadow-[0_0_0_3px_rgba(91,33,182,.1)] transition-all @error('password') border-red-500 @enderror"
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded text-violet-600" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="text-xs text-gray-600">Remember me</label>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="btn-shine w-full px-8 py-3.5 bg-violet-700 hover:bg-violet-800 text-white font-bold text-sm tracking-wider uppercase rounded-lg transition-all duration-300 hover:shadow-[0_4px_25px_rgba(91,33,182,.4)] flex items-center justify-center gap-2"
                >
                    <i class="fas fa-sign-in-alt text-violet-300"></i> Sign In
                </button>
            </form>

            <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                Student accounts use their ID number as the email address.
            </div>

            <!-- SPC Contact Info -->
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 mb-3">Need help? Contact SPC</p>
                <div class="flex items-center justify-center gap-6 text-xs text-gray-500 flex-wrap">
                    <span class="flex items-center gap-1.5"><i class="fas fa-phone text-violet-400 text-[10px]"></i> (088) 856 2609</span>
                    <span class="flex items-center gap-1.5"><i class="fas fa-envelope text-violet-400 text-[10px]"></i> registrar@spccdo.edu.ph</span>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-8">
            <a href="{{ url('/') }}" class="text-sm text-violet-600 hover:text-violet-700 font-medium"><i class="fas fa-arrow-left mr-1"></i> Back to Home</a>
        </div>
    </div>

</body>
</html>
