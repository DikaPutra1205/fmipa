@php
    use Illuminate\Support\Facades\Auth;
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Selamat datang Tenaga Ahli di Aplikasi Laravel</h1>
    @auth
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
    @endauth
</form>

</body>
</html>
