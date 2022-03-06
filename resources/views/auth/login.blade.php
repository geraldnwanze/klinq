<h1>Login</h1>

<x-alert />

<form action="{{ route('auth.login') }}" method="post">
    @csrf
    <input type="email" name="email" id="">
    <input type="password" name="password" id="">
    <button>login</button>
</form>

<a href="{{ route('auth.forgot-password') }}">forgot password</a>
<a href="{{ route('auth.register-page') }}">register ?</a>