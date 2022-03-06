<h1>Create New Password</h1>

<x-alert />

<form action="{{ route('auth.reset-password', $token) }}" method="post">
    @csrf
    @method('PUT')
    <input type="password" name="password" id="">
    <input type="password" name="confirm_password" id="">
    <button>update</button>
</form>
