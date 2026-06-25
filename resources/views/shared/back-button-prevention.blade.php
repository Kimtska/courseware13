<script>
window.addEventListener('pageshow', function(e) {
    if (e.persisted || (window.performance?.getEntriesByType?.('navigation')[0]?.type === 'back_forward')) {
        window.location.href = '/login';
    }
});
</script>
