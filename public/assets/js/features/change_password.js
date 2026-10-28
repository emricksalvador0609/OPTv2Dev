/* Change password page. Loaded only by resources/views/auth/change_password.blade.php. */
(() => {
    const form = document.getElementById('password-form');
    if (!form) return;

    const dialog = document.getElementById('required-password-dialog');
    if (dialog) {
        const notice = document.getElementById('password-notice');
        const backToNotice = () => {
            dialog.close();
            notice.showModal();
        };
        // Back and Esc leave the form but keep the requirement decision with the user.
        dialog.addEventListener('cancel', event => {
            event.preventDefault();
            backToNotice();
        });
        notice.addEventListener('cancel', event => event.preventDefault());
        document.getElementById('password-back').addEventListener('click', backToNotice);
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

    const save = document.getElementById('password-save');
    const loading = document.getElementById('password-loading');
    const back = document.getElementById('password-back');

    // Keep the button off until every rule passes; the server still checks on submit.
    function syncSubmitState() {
        const valid = form.checkValidity();
        if (save) save.disabled = !valid;
        return valid;
    }

    [password, confirmation, current].forEach(input => input.addEventListener('input', () => {
        validate();
        syncSubmitState();
    }));
    // Autofill can skip input events; re-check on change and once the page settles.
    form.addEventListener('change', () => {
        validate();
        syncSubmitState();
    });
    window.addEventListener('load', () => {
        validate();
        syncSubmitState();
    });

    form.addEventListener('submit', event => {
        validate();
        if (!syncSubmitState()) {
            event.preventDefault();
            return;
        }
        // Match the dashboard: cover the page until the server answers.
        if (loading) loading.removeAttribute('hidden');
        [save, back].forEach(button => {
            if (button) button.disabled = true;
        });
    });

    validate();
    syncSubmitState();
})();
