@if(!empty($passwordChangeNotice))
    <div class="alert alert-soft-warning alert-dismissible fade show" role="alert">
        <strong>Password update needed.</strong>
        Your password does not meet the company password policy.
        @if(!empty($passwordChangeNotice['deadline']))
            Please update it before {{ \Carbon\Carbon::parse($passwordChangeNotice['deadline'])->format('j M Y') }}.
        @else
            Please update it when convenient.
        @endif
        <a href="{{ route('password.edit') }}" class="btn btn-sm btn-warning ms-2">Change password</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
