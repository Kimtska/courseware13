<style>
    .swal-overlay{
        position:fixed;inset:0;z-index:9999;
        display:flex;align-items:center;justify-content:center;
        background:rgba(3,3,7,0.6);
        backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
        opacity:0;visibility:hidden;
        transition:opacity .3s ease, visibility .3s ease;
    }
    .swal-overlay.active{opacity:1;visibility:visible}
    .swal-modal{
        background:#fff;border-radius:20px;width:92%;max-width:420px;padding:0;overflow:hidden;
        box-shadow:0 25px 60px -12px rgba(30,5,82,0.35), 0 0 0 1px rgba(124,58,237,0.08);
        transform:scale(0.85) translateY(20px);opacity:0;
        transition:transform .35s cubic-bezier(0.34,1.56,0.64,1), opacity .3s ease;
    }
    .swal-overlay.active .swal-modal{transform:scale(1) translateY(0);opacity:1}
    .swal-overlay.closing{opacity:0;visibility:visible;transition:opacity .25s ease, visibility .25s ease}
    .swal-overlay.closing .swal-modal{transform:scale(0.9) translateY(10px);opacity:0;transition:transform .25s ease, opacity .25s ease}
    .swal-icon-wrap{position:relative;width:80px;height:80px;margin:0 auto}
    .swal-icon-ring{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#EDE9FE,#DDD6FE);display:flex;align-items:center;justify-content:center;position:relative}
    .swal-icon-ring::before{content:'';position:absolute;inset:-4px;border-radius:50%;background:conic-gradient(from 0deg, #7C3AED, #A78BFA, #C4B5FD, #7C3AED);z-index:-1;animation:swalRingRotate 4s linear infinite}
    @keyframes swalRingRotate{to{transform:rotate(360deg)}}
    .swal-icon-ring i{font-size:32px;background:linear-gradient(135deg,#5B21B6,#7C3AED);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .swal-dot{position:absolute;width:5px;height:5px;border-radius:50%;background:#A78BFA;animation:swalDotPulse 2s ease-in-out infinite}
    .swal-dot:nth-child(2){top:-8px;left:50%;transform:translateX(-50%);animation-delay:0s}
    .swal-dot:nth-child(3){top:50%;right:-8px;transform:translateY(-50%);animation-delay:0.5s}
    .swal-dot:nth-child(4){bottom:-8px;left:50%;transform:translateX(-50%);animation-delay:1s}
    .swal-dot:nth-child(5){top:50%;left:-8px;transform:translateY(-50%);animation-delay:1.5s}
    @keyframes swalDotPulse{0%,100%{opacity:.3;transform:scale(1)}50%{opacity:1;transform:scale(1.4)}}
    .swal-title{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:22px;color:#0f172a;margin:0}
    .swal-text{font-size:14px;color:#64748b;line-height:1.6;margin:0}
    .swal-btn{border:none;cursor:pointer;font-family:'Inter',sans-serif;font-weight:700;font-size:13px;padding:12px 24px;border-radius:12px;transition:all .2s ease;display:inline-flex;align-items:center;justify-content:center;gap:6px;letter-spacing:0.02em}
    .swal-btn:active{transform:scale(0.96)}
    .swal-btn-cancel{background:#f1f5f9;color:#475569}
    .swal-btn-cancel:hover{background:#e2e8f0;color:#334155}
    .swal-btn-logout{background:linear-gradient(135deg,#5B21B6,#7C3AED);color:#fff;box-shadow:0 4px 14px -3px rgba(91,33,182,0.4)}
    .swal-btn-logout:hover{background:linear-gradient(135deg,#4c1d95,#6D28D9);box-shadow:0 6px 20px -3px rgba(91,33,182,0.5);transform:translateY(-1px)}
    .swal-btn-logout:active{transform:translateY(0) scale(0.96)}
    .swal-spinner{width:14px;height:14px;border:2px solid rgba(255,255,255,0.3);border-top-color:#fff;border-radius:50%;animation:swalSpin .6s linear infinite;display:none}
    .swal-btn-logout.loading .swal-spinner{display:block}
    .swal-btn-logout.loading .swal-btn-text{display:none}
    @keyframes swalSpin{to{transform:rotate(360deg)}}
    .swal-bg-shape{position:absolute;pointer-events:none;opacity:0.04}
    @media(prefers-reduced-motion:reduce){.swal-modal,.swal-overlay{transition:none !important}.swal-icon-ring::before,.swal-dot{animation:none !important}}
</style>

<div id="swal-overlay" class="swal-overlay" role="dialog" aria-modal="true" aria-labelledby="swal-title" aria-describedby="swal-desc">
    <div class="swal-modal">
        <svg class="swal-bg-shape" style="top:-20px;right:-20px;width:120px;height:120px" viewBox="0 0 120 120"><circle cx="60" cy="60" r="60" fill="#7C3AED"></circle></svg>
        <svg class="swal-bg-shape" style="bottom:-30px;left:-30px;width:150px;height:150px" viewBox="0 0 150 150"><circle cx="75" cy="75" r="75" fill="#7C3AED"></circle></svg>
        <div class="pt-8 pb-2"><div class="swal-icon-wrap"><div class="swal-icon-ring"><i class="fas fa-sign-out-alt text-sm"></i></div><span class="swal-dot"></span><span class="swal-dot"></span><span class="swal-dot"></span><span class="swal-dot"></span></div></div>
        <div class="px-8 pt-4 pb-3 text-center"><h3 id="swal-title" class="swal-title">Ready to Leave?</h3><p id="swal-desc" class="swal-text mt-2">{{ $logoutDescription ?? 'You are about to end your session. Any unsaved progress in the current module may be lost.' }}</p></div>
        <div class="mx-8 mb-5 p-3 bg-violet-50/70 rounded-xl border border-violet-100/80"><div class="flex items-center gap-3"><div class="w-9 h-9 rounded-full bg-violet-200/60 flex items-center justify-center"><i class="fas fa-user text-violet-600 text-xs"></i></div><div class="flex-1 min-w-0"><p class="text-xs font-semibold text-violet-900 truncate">{{ $logoutLabel ?? 'User' }}</p><p class="text-[10px] text-violet-500">{{ $logoutSubtext ?? 'Session active' }}</p></div><div class="w-2 h-2 rounded-full bg-green-400 animate-pulse flex-shrink-0"></div></div></div>
        <div class="px-8 pb-8 flex items-center gap-3"><button id="swal-cancel" class="swal-btn swal-btn-cancel flex-1" type="button"><i class="fas fa-times text-xs"></i> Cancel</button><button id="swal-confirm" class="swal-btn swal-btn-logout btn-shine flex-1" type="button"><span class="swal-btn-text"><i class="fas fa-sign-out-alt text-sm"></i> Logout</span><span class="swal-spinner"></span></button></div>
    </div>
</div>

<script>
    (function () {
        const overlay = document.getElementById('swal-overlay');
        const cancelButton = document.getElementById('swal-cancel');
        const confirmButton = document.getElementById('swal-confirm');
        const logoutUrl = @json($logoutUrl ?? '/logout');
        const redirectUrl = @json($redirectUrl ?? '/');
        const csrfToken = @json(csrf_token());
        let isOpen = false;

        function resetButtons() {
            confirmButton.classList.remove('loading');
            confirmButton.disabled = false;
            confirmButton.style.pointerEvents = '';
            cancelButton.disabled = false;
            cancelButton.style.opacity = '';
            cancelButton.style.pointerEvents = '';
        }

        window.showLogoutAlert = function () {
            if (isOpen) return;
            isOpen = true;
            overlay.classList.remove('closing');
            overlay.classList.add('active');
            setTimeout(() => cancelButton.focus(), 350);
        };

        window.closeLogoutAlert = function () {
            if (!isOpen) return;
            overlay.classList.add('closing');
            overlay.classList.remove('active');
            setTimeout(() => { overlay.classList.remove('closing'); isOpen = false; resetButtons(); }, 280);
        };

        window.confirmLogout = function () {
            confirmButton.classList.add('loading');
            confirmButton.disabled = true;
            confirmButton.style.pointerEvents = 'none';
            cancelButton.disabled = true;
            cancelButton.style.opacity = '0.5';
            cancelButton.style.pointerEvents = 'none';

            fetch(logoutUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).finally(() => { window.location.href = redirectUrl; });
        };

        cancelButton.addEventListener('click', window.closeLogoutAlert);
        confirmButton.addEventListener('click', window.confirmLogout);
        overlay.addEventListener('click', function (event) { if (event.target === overlay) window.closeLogoutAlert(); });
        document.addEventListener('keydown', function (event) { if (event.key === 'Escape' && isOpen) window.closeLogoutAlert(); });
    })();
</script>