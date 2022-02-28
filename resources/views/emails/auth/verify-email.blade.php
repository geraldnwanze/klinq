@component('mail::message')
# Welcome to {{ config('app.name') }} <b>{{ $user->name }}</b>

Please click on the button below to verify your email

@component('mail::button', ['url' => route('verify-email', $user->userEmailVerify->token)])
Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
