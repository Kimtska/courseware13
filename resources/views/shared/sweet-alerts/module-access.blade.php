@php
    $isUnlocked = (bool) ($moduleState->is_unlocked ?? false);
    $actionLabel = $isUnlocked ? 'Lock Module Access' : 'Add Unlocked Access Module';
    $confirmTitle = $isUnlocked ? 'Lock module access?' : 'Unlock module access?';
    $confirmMessage = $isUnlocked
        ? 'This will lock access for all verified students in this module.'
        : 'This will unlock access for all verified students in this module.';
    $flash = session('module_access_flash');
    $successTitle = is_array($flash) && ! empty($flash['title'])
        ? $flash['title']
        : ($isUnlocked ? 'Module Locked' : 'Module Unlocked');
    $successMessage = is_array($flash) && ! empty($flash['message'])
        ? $flash['message']
        : ($isUnlocked
            ? 'The module has been locked and students can no longer access it.'
            : 'The module has been unlocked and students can now access it.');
@endphp

<style>
    .module-swal-overlay{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;background:rgba(15,23,42,.45);backdrop-filter:blur(10px);opacity:0;visibility:hidden;transition:opacity .25s ease, visibility .25s ease}
    .module-swal-overlay.active{opacity:1;visibility:visible}
    .module-swal-modal{background:#fff;border-radius:20px;width:min(92vw,430px);padding:0;overflow:hidden;box-shadow:0 25px 60px -12px rgba(30,5,82,.35);transform:scale(.9) translateY(18px);opacity:0;transition:transform .28s cubic-bezier(.34,1.56,.64,1), opacity .25s ease}
    .module-swal-overlay.active .module-swal-modal{transform:scale(1) translateY(0);opacity:1}
    .module-swal-title{font-family:'Space Grotesk',sans-serif;font-weight:700;font-size:22px;color:#0f172a;margin:0}
    .module-swal-text{font-size:14px;color:#64748b;line-height:1.6;margin:0}
    .module-swal-btn{border:none;cursor:pointer;font-family:'Inter',sans-serif;font-weight:700;font-size:13px;padding:12px 24px;border-radius:12px;transition:all .2s ease;display:inline-flex;align-items:center;justify-content:center;gap:6px;letter-spacing:.02em}
    .module-swal-btn:active{transform:scale(.96)}
    .module-swal-btn-cancel{background:#f1f5f9;color:#475569}
    .module-swal-btn-cancel:hover{background:#e2e8f0;color:#334155}
    .module-swal-btn-confirm{background:linear-gradient(135deg,#5B21B6,#7C3AED);color:#fff;box-shadow:0 4px 14px -3px rgba(91,33,182,.4)}
    .module-swal-btn-confirm:hover{background:linear-gradient(135deg,#4c1d95,#6D28D9);transform:translateY(-1px)}
    .module-swal-btn-success{background:linear-gradient(135deg,#047857,#10b981);color:#fff;box-shadow:0 4px 14px -3px rgba(16,185,129,.35)}
    @media (prefers-reduced-motion: reduce){.module-swal-overlay,.module-swal-modal{transition:none !important}}
</style>

<div id="module-swal-overlay" class="module-swal-overlay" role="dialog" aria-modal="true" aria-labelledby="module-swal-title" aria-describedby="module-swal-desc">
    <div class="module-swal-modal">
        <div class="px-8 pt-7 pb-3 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-violet-100 text-violet-700 flex items-center justify-center mb-4">
                <i id="module-swal-icon" class="fas {{ $isUnlocked ? 'fa-lock' : 'fa-lock-open' }} text-2xl"></i>
            </div>
            <h3 id="module-swal-title" class="module-swal-title">{{ $confirmTitle }}</h3>
            <p id="module-swal-desc" class="module-swal-text mt-2">{{ $confirmMessage }}</p>
        </div>
        <div class="px-8 pb-8 flex items-center gap-3">
            <button id="module-swal-cancel" class="module-swal-btn module-swal-btn-cancel flex-1" type="button">
                <i class="fas fa-times text-xs"></i> Cancel
            </button>
            <button id="module-swal-confirm" class="module-swal-btn module-swal-btn-confirm flex-1" type="button">
                <span class="module-swal-btn-text"><i class="fas fa-check text-xs"></i> Confirm</span>
            </button>
        </div>
    </div>
</div>

<div id="module-swal-success" class="module-swal-overlay" role="status" aria-modal="true" aria-labelledby="module-swal-success-title" aria-describedby="module-swal-success-desc">
    <div class="module-swal-modal">
        <div class="px-8 pt-7 pb-3 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4">
                <i class="fas fa-circle-check text-2xl"></i>
            </div>
            <h3 id="module-swal-success-title" class="module-swal-title">{{ $successTitle }}</h3>
            <p id="module-swal-success-desc" class="module-swal-text mt-2">{{ $successMessage }}</p>
        </div>
        <div class="px-8 pb-8">
            <button id="module-swal-success-close" class="module-swal-btn module-swal-btn-success w-full" type="button">
                <i class="fas fa-check text-xs"></i> OK
            </button>
        </div>
    </div>
</div>

<script>
(() => {
    const overlay = document.getElementById('module-swal-overlay');
    const successOverlay = document.getElementById('module-swal-success');
    const cancelButton = document.getElementById('module-swal-cancel');
    const confirmButton = document.getElementById('module-swal-confirm');
    const successCloseButton = document.getElementById('module-swal-success-close');
    const form = document.querySelector('[data-module-access-form]');
    const triggerButton = document.querySelector('[data-module-access-button]');

    if (!overlay || !form || !triggerButton) {
        return;
    }

    const openOverlay = (target) => {
        target.classList.add('active');
    };

    const closeOverlay = (target) => {
        target.classList.remove('active');
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        openOverlay(overlay);
    });

    cancelButton?.addEventListener('click', () => closeOverlay(overlay));
    confirmButton?.addEventListener('click', () => {
        closeOverlay(overlay);
        form.submit();
    });

    successCloseButton?.addEventListener('click', () => closeOverlay(successOverlay));

    const flash = @json($flash);
    if (flash) {
        window.setTimeout(() => openOverlay(successOverlay), 80);
    }
})();
</script>
