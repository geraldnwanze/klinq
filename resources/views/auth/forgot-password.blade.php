<h1>Forgot Password</h1>

<x-alert />

<form action="{{ route('auth.forgot-password') }}" method="post">
    @csrf
    <input type="email" name="email" id="">
    <button>submit</button>
</form>
