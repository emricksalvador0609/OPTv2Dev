/* Audit Log page. Loaded only by resources/views/audit_log.blade.php. */
(() => {
    const form = document.querySelector('form[role="search"]');
    if (!form) return;
    const input = form.querySelector('input[name="q"]');
    if (!input) return;

    // Keep the search usable on the first keystroke pause, not on every character.
    let timer = null;
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => form.requestSubmit(), 500);
    });
    form.addEventListener('submit', () => clearTimeout(timer));
})();
