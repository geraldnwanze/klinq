@component('mail::message')
# Hello {{ $name }}

Click on the link below to reset your password

@component('mail::button', ['url' => route('auth.reset-password', $token)])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
