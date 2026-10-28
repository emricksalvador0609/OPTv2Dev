<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change password | OPT v2.0</title>
    <link href="{{ asset_v('assets/css/theme.min.css') }}" rel="stylesheet">
    <link href="{{ asset_v('assets/css/features/change_password.css') }}" rel="stylesheet">
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
    @if($requiredChange)<p id="password-required-message" class="text-danger fs--1">Please update your password to continue using OPT. Sign out ends your session.</p>@endif
    <hr>
    <form method="POST" action="{{ route('password.update') }}" id="password-form" novalidate
          data-rules='@json($rules)' data-has-errors="{{ $errors->any() ? '1' : '0' }}">
        @csrf
        <div class="password-field">
            <label for="current-password">CURRENT PASSWORD</label>
            <input class="form-control @error('current_password') is-invalid @enderror" id="current-password" name="current_password" type="password" autocomplete="current-password" aria-describedby="current-password-feedback" required>
            <div class="invalid-feedback" id="current-password-feedback">@error('current_password'){{ $message }}@enderror</div>
        </div>
        <div class="password-field">
            <label for="new-password">NEW PASSWORD</label>
            <input class="form-control @error('password') is-invalid @enderror" id="new-password" name="password" type="password" autocomplete="new-password" aria-describedby="password-rules password-feedback" required>
            <div class="invalid-feedback" id="password-feedback">@error('password'){{ $message }}@enderror</div>
            <ul class="password-rules" id="password-rules" aria-live="polite">
                @foreach($rules as $rule)
                    <li class="text-danger" data-rule="{{ $loop->index }}"><span class="rule-mark" aria-hidden="true">×</span><span>{{ $rule['message'] }}</span></li>
                @endforeach
            </ul>
        </div>
        <div class="password-field">
            <label for="confirm-password">CONFIRM NEW PASSWORD</label>
            <input class="form-control @error('password') is-invalid @enderror" id="confirm-password" name="password_confirmation" type="password" autocomplete="new-password" aria-describedby="password-match" required>
            <ul class="password-rules" aria-live="polite"><li id="password-match" class="text-danger" hidden><span class="rule-mark" aria-hidden="true">×</span><span class="match-text">Passwords do not match</span></li></ul>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" id="password-save" class="btn btn-primary btn-sm">Save password</button>
            @if($requiredChange)
                <button type="button" id="password-back" class="btn btn-phoenix-secondary btn-sm">Back</button>
            @else
                <a class="btn btn-phoenix-secondary btn-sm" href="{{ route('dashboard_admin') }}">Back</a>
            @endif
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
<div class="loading" id="password-loading" hidden>
    <div class="spinner">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<script src="{{ asset_v('assets/js/features/change_password.js') }}"></script>
</body>
</html>
