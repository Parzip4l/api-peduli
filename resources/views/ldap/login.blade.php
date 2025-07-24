<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LDAP Login Test</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #f2f2f2; }
        .card { background: white; padding: 2rem; border-radius: 8px; max-width: 400px; margin: auto; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="card">
        <h2>Test Login LDAP</h2>

        @if (session('success'))
            <div style="color: green;">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div style="color: red;">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('ldap.login') }}">
            @csrf
            <div>
                <label>Username:</label>
                <input type="text" name="username" value="{{ old('username') }}" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-top: 10px;">
                <label>Password:</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-top: 20px;">
                <button type="submit" style="padding: 10px 20px;">Login</button>
            </div>
        </form>
    </div>
</body>
</html>
