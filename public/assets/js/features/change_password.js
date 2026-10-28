/* Change password page. Loaded only by resources/views/auth/change_password.blade.php. */
(() => {
    const form = document.getElementById('password-form');
    if (!form) return;

    const dialog = document.getElementById('required-password-dialog');
    if (dialog) {
        const notice = document.getElementById('password-notice');
        dialog.addEventListener('cancel', event => event.preventDefault());
        notice.addEventListener('cancel', event => event.preventDefault());
        document.getElementById('notice-change-password').addEventListener('click', () => {
            notice.close();
            dialog.showModal();
            document.getElementById('current-password').focus();
        });
        // A validation failure returns directly to the form so the user can correct it.
        if (form.dataset.hasErrors === '1') {
            dialog.showModal();
        } else {
            notice.showModal();
        }
    }

    const rules = JSON.parse(form.dataset.rules || '[]');
    const password = document.getElementById('new-password');
    const confirmation = document.getElementById('confirm-password');
    const current = document.getElementById('current-password');

    function markField(input, valid) {
        // Leave empty fields alone so a server-rendered error stays visible.
        if (input.value.length === 0) return;
        input.classList.toggle('is-invalid', !valid);
        input.classList.toggle('is-valid', valid);
    }

    function validate() {
        const value = password.value;
        let error = '';
        rules.forEach((rule, index) => {
            const valid = rule.minimum !== undefined
                ? Array.from(value).length >= rule.minimum
                : new RegExp(rule.pattern, 'u').test(value);
            const item = document.querySelector('[data-rule="' + index + '"]');
            item.classList.toggle('text-success', valid);
            item.classList.toggle('text-danger', !valid);
            item.querySelector('.rule-mark').textContent = valid ? '✓' : '×';
            if (!valid && !error) error = rule.message;
        });
        if (!error && value === current.value) error = 'Choose a password different from your current password.';
        password.setCustomValidity(error);
        markField(password, error === '');

        const matches = value.length > 0 && value === confirmation.value;
        const match = document.getElementById('password-match');
        match.hidden = confirmation.value.length === 0;
        match.classList.toggle('text-success', matches);
        match.classList.toggle('text-danger', !matches);
        match.querySelector('.rule-mark').textContent = matches ? '✓' : '×';
        match.querySelector('.match-text').textContent = matches ? 'Passwords match' : 'Passwords do not match';
        confirmation.setCustomValidity(confirmation.value.length === 0 || matches ? '' : 'Passwords do not match');
        markField(confirmation, matches);
    }

    [password, confirmation, current].forEach(input => input.addEventListener('input', validate));
    // Client hints stay helpers only; the server validates again on submit.
    form.addEventListener('submit', event => {
        validate();
        if (!form.checkValidity()) event.preventDefault();
    });
    validate();
})();
