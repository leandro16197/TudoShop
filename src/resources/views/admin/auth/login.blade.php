<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso al Panel | ShopTudo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background: #0f172a; 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8fafc;
        }

        .container { 
            width: 100%;
            max-width: 420px; 
            background: #1e293b; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255,255,255,0.05);
        }

        h2 { 
            text-align: center; 
            margin-bottom: 24px; 
            font-size: 1.5rem;
            color: #fff;
        }

        .form-group { margin-bottom: 20px; }

        label { 
            display: block; 
            margin-bottom: 8px; 
            font-size: 0.875rem;
            color: #94a3b8; 
            font-weight: 600;
        }

        input[type="email"], 
        input[type="password"] { 
            width: 100%; 
            padding: 12px 16px; 
            background: #0f172a;
            border: 1px solid #334155; 
            border-radius: 8px; 
            color: #fff;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        .error { 
            color: #fb7185; 
            font-size: 0.8rem; 
            margin-top: 6px; 
        }

        .success { 
            background: rgba(34, 197, 94, 0.1);
            color: #4ade80; 
            padding: 12px;
            border-radius: 8px;
            font-size: 0.875rem; 
            margin-bottom: 20px; 
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        button { 
            width: 100%;
            padding: 12px; 
            background: #6366f1; 
            color: #fff; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.2s;
            margin-top: 10px;
        }

        button:hover { background: #4f46e5; }

        .flex { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-top: 20px;
        }

        .checkbox { 
            display: flex; 
            align-items: center; 
            cursor: pointer;
        }

        .checkbox input { 
            margin-right: 8px; 
            accent-color: #6366f1;
            cursor: pointer;
        }

        .checkbox label { margin-bottom: 0; cursor: pointer; }

        a { 
            color: #818cf8; 
            text-decoration: none; 
            font-size: 0.875rem;
            transition: color 0.2s;
        }

        a:hover { color: #6366f1; text-decoration: underline; }
        .logo {
            display: block;
            margin: 0 auto 20px;
            width: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bienvenido a ShopTudo</h2>

        @if(session('status'))
            <div class="success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="tu@email.com" required autofocus>
                @if($errors->has('email'))
                    <div class="error">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" placeholder="••••••••" required>
                @if($errors->has('password'))
                    <div class="error">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="form-group checkbox">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Recordarme en este equipo</label>
            </div>

            <button type="submit">Iniciar sesión</button>

            <div class="flex">
                @if(Route::has('password.request'))
                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
            </div>

        </form>
    </div>
</body>
</html>