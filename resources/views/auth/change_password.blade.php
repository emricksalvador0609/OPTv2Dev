<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change password | OPT v2.0</title>
    <link href="{{ asset('assets/css/theme.min.css') }}" rel="stylesheet">
    <style>
        body { background: #f5f7fa; color: #0b1930; }
        .password-card { width: 100%; max-width: 416px; margin: 28px auto; padding: 22px; background: white; border: 1px solid #e3e6ed; }
        .password-card h1 { font-size: 23px; margin: 0 0 8px; }
        .password-subtitle { color: #526b91; margin-bottom: 18px; }
        .password-card hr { margin-bottom: 28px; }
        .password-card label { display: block; font-size: 10px; margin: 0 0 4px 15px; }
        .password-card .form-control { min-height: 35px; }
        .password-field { margin-bottom: 20px; }
        .password-rules { list-style: none; padding: 0 3px; margin: 8px 0 0; font-size: 11px; }
        .password-rules li { color: #e33922; margin: 2px 0; }
        .password-rules li.valid { color: #227442; }
        .rule-mark { display: inline-block; width: 16px; }
        .password-advisory { border-left: 2px solid #999; background: #f5f5f5; padding: 13px; margin-top: 22px; font-size: 11px; line-height: 1.7; }
        .password-advisory p { margin: 0; }
        .password-advisory .advisory-rules { color: #cf371f; }
        dialog.password-card { max-height: calc(100dvh - 32px); overflow-y: auto; margin: auto; color: #0b1930; }
        dialog.password-card::backdrop { background: rgba(11, 25, 48, .65); }
        .password-notice { width: calc(100% - 24px); max-width: 450px; padding: 0; border: 0; border-radius: 5px; color: #525b75; }
        .password-notice::backdrop { background: rgba(0, 0, 0, .72); }
        .password-notice header { padding: 17px 15px; border-bottom: 1px solid #dfe3eb; }
        .password-notice h2 { margin: 0; font-size: 18px; color: #454f69; }
        .password-notice-body { padding: 15px; font-size: 14px; line-height: 1.6; }
        .password-notice-body p:last-child { margin-bottom: 0; }
        .password-notice footer { display: flex; justify-content: flex-end; gap: 8px; padding: 14px; border-top: 1px solid #dfe3eb; }
        @media (max-width: 440px) { .password-card { margin: 0 auto; } }
    </style>
</head>
<body>
@if($requiredChange)
<dialog class="password-notice" id="password-notice" aria-labelledby="password-notice-title" aria-describedby="password-notice-description">
    <header><h2 id="password-notice-title">Password update required</h2></header>
    <div class="password-notice-body">
        <p id="password-notice-description">As part of our organization's ISO compliance requirements, please update your password to meet the following requirements before continuing to OPT.</p>
        <ul>@foreach($rules as $rule)<li>{{ $rule['message'] }}</li>@endforeach</ul>
        <p>Choose Change password to update your password, or Sign out to leave OPT.</p>
    </div>
    <footer>
        <button type="submit" form="password-exit" class="btn btn-phoenix-secondary btn-sm">Sign out</button>
        <button type="button" id="notice-change-password" class="btn btn-primary btn-sm">Change password</button>
    </footer>
</dialog>
@endif
@if($requiredChange)
<dialog class="password-card" id="required-password-dialog" aria-labelledby="password-title" aria-describedby="password-required-message">
@else
<main class="password-card">
@endif
    <h1 id="password-title">Change password for {{ $username }}</h1>
    <p class="password-subtitle">Update your account password</p>
    @if($requiredChange)<p id="password-required-message" class="text-danger fs--1">Please update your password to continue using OPT. Exit will sign you out.</p>@endif
    <hr>
    @if(session('password_updated'))
        <div class="alert alert-success" role="status">{{ session('password_updated') }} <a href="{{ route('dashboard_admin') }}">Continue to OPT</a></div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('password.update') }}" id="password-form">
        @csrf
        <div class="password-field">
            <label for="current-password">CURRENT PASSWORD</label>
            <input class="form-control" id="current-password" name="current_password" type="password" autocomplete="current-password" required>
        </div>
        <div class="password-field">
            <label for="new-password">NEW PASSWORD</label>
            <input class="form-control" id="new-password" name="password" type="password" autocomplete="new-password" aria-describedby="password-rules" required>
            <ul class="password-rules" id="password-rules" aria-live="polite">
                @foreach($rules as $rule)
                    <li data-rule="{{ $loop->index }}"><span class="rule-mark" aria-hidden="true">×</span><span>{{ $rule['message'] }}</span></li>
                @endforeach
            </ul>
        </div>
        <div class="password-field">
            <label for="confirm-password">CONFIRM NEW PASSWORD</label>
            <input class="form-control" id="confirm-password" name="password_confirmation" type="password" autocomplete="new-password" aria-describedby="password-match" required>
            <ul class="password-rules" aria-live="polite"><li id="password-match" hidden><span class="rule-mark" aria-hidden="true">×</span><span class="match-text">Passwords do not match</span></li></ul>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">Save password</button>
            <button type="submit" form="password-exit" class="btn btn-phoenix-secondary btn-sm">Exit</button>
        </div>
    </form>
    <form id="password-exit" method="POST" action="{{ route('password.exit') }}">@csrf</form>
    <aside class="password-advisory">
        <p class="mb-1">Advisory</p>
        <div class="advisory-rules">
            <p>In compliance with I.S.O., kindly change your password using the following format:</p>
            @foreach($rules as $rule)<p>* {{ $rule['message'] }}</p>@endforeach
        </div>
    </aside>
@if($requiredChange)</dialog>@else</main>@endif
<script>
(() => {
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
        if (@json($errors->any())) {
            dialog.showModal();
        } else {
            notice.showModal();
        }
    }
    const rules = @json($rules);
    const password = document.getElementById('new-password');
    const confirmation = document.getElementById('confirm-password');
    const current = document.getElementById('current-password');
    function validate() {
        const value = password.value;
        let error = '';
        rules.forEach((rule, index) => {
            const valid = rule.minimum !== undefined ? Array.from(value).length >= rule.minimum : new RegExp(rule.pattern, 'u').test(value);
            const item = document.querySelector('[data-rule="' + index + '"]');
            item.classList.toggle('valid', valid);
            item.querySelector('.rule-mark').textContent = valid ? '✓' : '×';
            if (!valid && !error) error = rule.message;
        });
        if (!error && value === current.value) error = 'Choose a password different from your current password.';
        password.setCustomValidity(error);
        const matches = value.length > 0 && value === confirmation.value;
        const match = document.getElementById('password-match');
        match.hidden = confirmation.value.length === 0;
        match.classList.toggle('valid', matches);
        match.querySelector('.rule-mark').textContent = matches ? '✓' : '×';
        match.querySelector('.match-text').textContent = matches ? 'Passwords match' : 'Passwords do not match';
        confirmation.setCustomValidity(confirmation.value.length === 0 || matches ? '' : 'Passwords do not match');
    }
    [password, confirmation, current].forEach(input => input.addEventListener('input', validate));
    validate();
})();
</script>
</body>
</html>
