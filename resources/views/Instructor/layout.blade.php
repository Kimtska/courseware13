<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Instructor') - IOT-Based Marksmanship</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/Marksmanship innovatech.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body{font-family:'Inter',sans-serif;margin:0;background:#f8fafc;overflow:hidden;height:100vh}
        .sidebar-link{display:flex;align-items:center;gap:12px;padding:10px 16px;border-radius:8px;color:rgba(255,255,255,0.6);transition:all .2s;font-size:14px;border-left:4px solid transparent;margin-bottom:2px;white-space:nowrap}
        .sidebar-link:hover{background:rgba(255,255,255,0.1);color:#fff;border-left-color:#8B5CF6}
        .sidebar-link.active{background:rgba(255,255,255,0.15);color:#fff;border-left-color:#A78BFA;font-weight:600}
        .dash-card{background:#fff;border:1px solid #f1f5f9;border-radius:12px;transition:all .3s}.dash-card:hover{box-shadow:0 10px 25px -5px rgba(0,0,0,.05);transform:translateY(-2px)}
        #sidebar { width: 256px; transition: width 0.3s; }
        #sidebar.collapsed { width: 80px; }
        #sidebar.collapsed .sidebar-text { display: none; }
        #sidebar.collapsed .sidebar-header-text { display: none; }
        #sidebar.collapsed .sidebar-profile-text { display: none; }
        #sidebar.collapsed .sidebar-link { justify-content: center; padding-left: 0; padding-right: 0; border-left: none; }
    </style>
    @include('shared.back-button-prevention')
</head>
<body class="flex h-screen">
    @php
        if (!isset($name) || $name === null || $name === '') {
            $name = auth()->user()->name ?? 'Instructor';
        }
        $name = $name ?? 'Instructor';
        $profilePhoto = auth()->user()->profile_photo_path ?? null;
    @endphp
    @include('Instructor.partials.nav-links')

    <main class="flex-1 overflow-y-auto bg-gray-50">
        <header class="bg-white border-b border-gray-100 px-6 py-3 flex justify-between items-center sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-500 focus:outline-none">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div><h1 class="font-display font-bold text-xl text-gray-900">@yield('pageTitle', 'Instructor')</h1><p class="text-xs text-gray-400">@yield('pageSubtitle')</p></div>
            </div>
            @yield('headerActions')
        </header>

        <div class="px-4 py-4 sm:px-2 sm:py-2">
            @yield('content')
        </div>
    </main>

    <!-- Settings Modal -->
    <div id="settings-modal" class="fixed inset-0 z-50 hidden opacity-0 pointer-events-none items-center justify-center p-4 modal-backdrop transition-opacity duration-200 ease-out backdrop-blur-sm bg-slate-950/35">
        <div class="modal-panel w-full max-w-4xl rounded-3xl bg-white shadow-2xl overflow-hidden transform scale-95 translate-y-3 opacity-0 transition-all duration-200 ease-out">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-display font-bold text-xl text-gray-900">Profile Settings</h3>
                    <p class="text-sm text-gray-500">Update your name or change your password.</p>
                </div>
                <button type="button" class="close-settings w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors"><i class="fas fa-xmark"></i></button>
            </div>
            <div class="p-6">
                @if(session('success'))
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 mb-6">
                        <i class="fas fa-circle-check mr-2"></i>{{ session('success') }}
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-display font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-camera text-violet-600"></i> Change Profile Photo
                            </h4>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full bg-violet-100 flex items-center justify-center text-xl font-bold text-violet-700 flex-shrink-0 overflow-hidden">
                                    @if($profilePhoto)
                                        <img src="{{ asset('storage/' . $profilePhoto) }}" alt="Photo" class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <form method="POST" action="{{ route('instructor.profile.photo') }}" enctype="multipart/form-data" class="flex-1">
                                    @csrf
                                    <div class="flex items-center gap-2">
                                        <input type="file" name="photo" id="profile-photo-input" accept="image/jpeg,image/png,image/gif,image/webp" class="hidden" required>
                                        <label for="profile-photo-input" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold hover:bg-gray-200 transition-colors cursor-pointer whitespace-nowrap">
                                            <i class="fas fa-folder-open"></i> Choose File
                                        </label>
                                        <span id="file-name-display" class="text-xs text-gray-400 truncate max-w-[140px]">No file chosen</span>
                                        <button type="submit" class="px-4 py-3 rounded-xl bg-violet-700 text-white text-xs font-bold hover:bg-violet-800 transition-colors whitespace-nowrap">
                                            <i class="fas fa-upload"></i> Upload
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-2">JPEG, PNG, GIF, WebP up to 2MB.</p>
                                    @error('photo')
                                        <p class="mt-1 text-xs text-red-500"><i class="fas fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                                    @enderror
                                </form>
                            </div>
                        </div>
                        <div class="pt-6 border-t border-gray-100">
                            <h4 class="font-display font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                                <i class="fas fa-user-edit text-violet-600"></i> Edit Name
                            </h4>
                            <form method="POST" action="{{ route('instructor.profile.name') }}">
                                @csrf
                                @method('PATCH')
                                <div class="flex items-center gap-3">
                                    <div class="flex-1">
                                        <input type="text" name="name" value="{{ $name }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                                    </div>
                                    <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors whitespace-nowrap">
                                        <i class="fas fa-check"></i> Save
                                    </button>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-xs text-red-500"><i class="fas fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                                @enderror
                            </form>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-display font-bold text-base text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-lock text-violet-600"></i> Change Password
                        </h4>
                        <form method="POST" action="{{ route('instructor.profile.password') }}">
                            @csrf
                            @method('PATCH')
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Current Password</label>
                                    <input type="password" name="current_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">New Password</label>
                                    <input type="password" name="new_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required minlength="8">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-4 focus:ring-violet-100 focus:border-violet-400 text-sm" required minlength="8">
                                </div>
                            </div>
                            @error('current_password')
                                <p class="mt-2 text-xs text-red-500"><i class="fas fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                            @error('new_password')
                                <p class="mt-2 text-xs text-red-500"><i class="fas fa-triangle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                            <div class="mt-4">
                                <button type="submit" class="px-5 py-3 rounded-xl bg-violet-700 text-white text-sm font-bold hover:bg-violet-800 transition-colors">
                                    <i class="fas fa-check"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('shared.sweet-alerts.logout', ['logoutLabel' => $name, 'logoutSubtext' => 'Instructor session active', 'logoutDescription' => 'You are about to end your instructor session. Please make sure your class records and assessments are saved before logging out.', 'redirectUrl' => url('/login')])

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebar-toggle');
        toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('collapsed'); });

        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') {
                    e.preventDefault();
                    this.closest('nav').querySelectorAll('.sidebar-link').forEach(l=>l.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });

        const settingsModal = document.getElementById('settings-modal');
        const settingsBtn = document.getElementById('sidebar-settings-btn');
        const modalAnimationDelay = 200;

        function openModal(modal) {
            const panel = modal.querySelector('.modal-panel');
            modal.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
            modal.classList.add('flex');
            requestAnimationFrame(() => {
                modal.classList.add('opacity-100');
                if (panel) {
                    panel.classList.remove('scale-95', 'translate-y-3', 'opacity-0');
                    panel.classList.add('scale-100', 'translate-y-0', 'opacity-100');
                }
            });
        }

        function closeModal(modal) {
            const panel = modal.querySelector('.modal-panel');
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0', 'pointer-events-none');
            if (panel) {
                panel.classList.remove('scale-100', 'translate-y-0', 'opacity-100');
                panel.classList.add('scale-95', 'translate-y-3', 'opacity-0');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, modalAnimationDelay);
        }

        settingsBtn?.addEventListener('click', () => openModal(settingsModal));

        document.querySelectorAll('.close-settings').forEach(button => {
            button.addEventListener('click', () => closeModal(settingsModal));
        });

        document.getElementById('profile-photo-input')?.addEventListener('change', function() {
            const display = document.getElementById('file-name-display');
            display.textContent = this.files[0] ? this.files[0].name : 'No file chosen';
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeModal(settingsModal);
        });
    </script>
</body>
</html>
