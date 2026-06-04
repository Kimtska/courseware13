@php
    $currentModuleKey = $currentModuleKey ?? null;
    $currentModuleLabel = $currentModuleLabel ?? 'this module';
    $redirectUrl = $redirectUrl ?? route('student.dashboard');
@endphp

@if ($currentModuleKey && !request()->boolean('embedded'))
<div id="module-revoked-overlay" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/45 px-4">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-rose-100 p-6 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-rose-50 text-rose-600">
            <i class="fas fa-triangle-exclamation text-2xl"></i>
        </div>
        <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-rose-500">Access Revoked</p>
        <h3 id="module-revoked-title" class="mt-2 text-xl font-bold text-gray-900">Module access disabled</h3>
        <p id="module-revoked-message" class="mt-2 text-sm leading-6 text-gray-600">Instructor disabled access to {{ $currentModuleLabel }}. You can no longer access this module.</p>
        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
            <button id="module-revoked-back" class="inline-flex items-center justify-center rounded-xl bg-violet-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-violet-600 transition-colors">
                Back to Dashboard
            </button>
        </div>
    </div>
</div>

<script>
    (function () {
        const currentModuleKey = @json($currentModuleKey);
        const currentModuleLabel = @json($currentModuleLabel);
        const redirectUrl = @json($redirectUrl);
        const statesUrl = "{{ route('student.module-states') }}";
        const overlay = document.getElementById('module-revoked-overlay');
        const backBtn = document.getElementById('module-revoked-back');
        let alreadyRevoked = false;
        let pollTimer = null;

        function showRevokedAlert() {
            if (alreadyRevoked || !overlay) return;
            alreadyRevoked = true;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');

            // Auto return to dashboard after a short delay
            setTimeout(() => {
                window.location.href = redirectUrl;
            }, 3500);
        }

        function handleModuleStates(modules) {
            try {
                const moduleState = modules?.[currentModuleKey];
                if (moduleState && !moduleState.is_unlocked) {
                    showRevokedAlert();
                }
            } catch (error) {
                console.error('Module revocation handler error', error);
            }
        }

        backBtn?.addEventListener('click', function () {
            window.location.href = redirectUrl;
        });

        // If nav already fetched states, use them; otherwise listen for updates
        if (window.__moduleStates) {
            handleModuleStates(window.__moduleStates);
        }
        window.addEventListener('moduleStatesUpdated', function (e) {
            handleModuleStates(e.detail);
        });
    })();
</script>
@endif