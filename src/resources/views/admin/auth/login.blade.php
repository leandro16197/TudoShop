<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { max-width: 400px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .error { color: red; font-size: 0.875rem; margin-top: 5px; }
        .success { color: green; font-size: 0.875rem; margin-bottom: 10px; }
        button { padding: 10px 20px; background: #4f46e5; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #4338ca; }
        .flex { display: flex; justify-content: space-between; align-items: center; }
        .checkbox { display: flex; align-items: center; }
        .checkbox input { margin-right: 5px; }
        a { color: #4f46e5; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">

        @if(session('status'))
            <div class="success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @if($errors->has('email'))
                    <div class="error">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" required>
                @if($errors->has('password'))
                    <div class="error">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="form-group checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Recordarme</label>
            </div>

            <div class="flex">
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
                <button type="submit">Iniciar sesión</button>
            </div>

        </form>
    </div>
</body>
</html>
