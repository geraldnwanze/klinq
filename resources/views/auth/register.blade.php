<h1>Register</h1>

<x-alert />

<form action="{{ route('auth.register') }}" method="post">
    @csrf
    <input type="text" name="name" id="">
    <input type="email" name="email" id="">
    <input type="password" name="password" id="">
    <button>register</button>
</form>

<a href="{{ route('auth.login-page') }}">login ?</a>