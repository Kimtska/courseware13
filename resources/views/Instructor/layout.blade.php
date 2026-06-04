<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Instructor') - VirtualArm</title>
    <link rel="icon" type="image/png" href="{{ asset('images/assets/logo.png') }}">
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
</head>
<body class="flex h-screen">
    @include('Instructor.partials.nav-links')

    <main class="flex-1 overflow-y-auto bg-gray-50">
        <header class="bg-white border-b border-gray-100 px-6 py-3 flex justify-between items-center sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button id="sidebar-toggle" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors text-gray-500 focus:outline-none">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <div><h1 class="font-display font-bold text-xl text-black">@yield('pageTitle', 'Instructor')</h1><p class="text-xs text-gray-400">@yield('pageSubtitle')</p></div>
            </div>
            @yield('headerActions')
        </header>

        <div class="px-4 py-4 sm:px-2 sm:py-2">
            @yield('content')
        </div>
    </main>

    @include('shared.sweet-alerts.logout', ['logoutLabel' => $name ?? 'Instructor', 'logoutSubtext' => 'Instructor session active', 'logoutDescription' => 'You are about to end your instructor session. Please make sure your class records and assessments are saved before logging out.', 'redirectUrl' => url('/')])

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
    </script>
</body>
</html>
