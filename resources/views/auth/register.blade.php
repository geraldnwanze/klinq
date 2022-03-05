<x-alert />

<form action="{{ route('register') }}" method="post">
    @csrf
    <input type="text" name="name" id="">
    <input type="email" name="email" id="">
    <input type="password" name="password" id="">
    <button>submit</button>
</form>
