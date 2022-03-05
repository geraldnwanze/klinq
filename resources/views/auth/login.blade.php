<h1>Login</h1>

<x-alert />

<form action="{{ route('login') }}" method="post">
    @csrf
    <input type="email" name="email" id="">
    <input type="password" name="password" id="">
    <button>login</button>
</form>
