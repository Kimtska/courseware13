<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT-Based Marksmanship - Verify Code</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-50 grid-pattern min-h-screen flex items-center justify-center p-4">

    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-violet-100/40 rounded-full blur-[200px]"></div>
    </div>

    <div class="max-w-xl w-full relative z-10 fade-in-up visible mx-auto">
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-3 mb-6">
                <img src="{{ asset('images/assets/Marksmanship innovatech.png') }}" alt="SPC" class="h-12 w-auto">
                <div class="flex flex-col ml-3">
                    <span class="font-display font-bold text-lg text-black leading-none tracking-tight">IOT-Based<span class="text-violet-700"> Marksmanship</span></span>
                    <span class="text-[9px] text-gray-400 tracking-wider uppercase font-medium">SPC Criminology</span>
                </div>
            </div>
        </div>

        <div class="p-8 md:p-12 rounded-2xl bg-white border border-violet-100 shadow-xl">
            <div class="text-center mb-8">
                <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Verification Required</h1>
                <p class="text-sm text-gray-500 mt-2">Too many failed login attempts. A 6-digit code was sent to <strong>{{ $maskedEmail }}</strong>.</p>
            </div>

            <form method="POST" action="{{ route('login.verify.submit') }}">
                @csrf

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Verification Code</label>
                    <input type="text" name="code" value="{{ old('code') }}" required maxlength="6" placeholder="000000"
                        class="w-full text-center text-2xl tracking-[12px] font-mono font-bold px-4 py-4 rounded-xl border @error('code') border-red-300 ring-4 ring-red-50 @else border-gray-200 focus:ring-4 focus:ring-violet-100 focus:border-violet-400 @enderror outline-none transition-all"
                        autocomplete="off" inputmode="numeric" pattern="[0-9]*">

                    @error('code')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3.5 rounded-xl bg-violet-700 text-white font-bold text-sm hover:bg-violet-800 transition-all shadow-lg shadow-violet-200">
                    Verify & Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-violet-600 hover:text-violet-800 font-medium">Back to Login</a>
            </div>
        </div>
    </div>

</body>
</html>
