<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f6f2ea;
            --card: #ffffff;
            --ink: #1e1b16;
            --muted: #6b6257;
            --accent: #1e7a64;
            --accent-dark: #145b4b;
            --border: #e6dfd4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Avenir Next", "Segoe UI", Tahoma, sans-serif;
            background: radial-gradient(1200px 600px at 20% -10%, #fff1da, transparent),
                radial-gradient(800px 500px at 90% 0%, #e7f7f1, transparent),
                var(--bg);
            color: var(--ink);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }

        .card {
            width: min(420px, 100%);
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 24px 60px rgba(30, 27, 22, 0.12);
            animation: float-in 0.6s ease-out;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
            letter-spacing: -0.02em;
        }

        p {
            margin: 0 0 20px;
            color: var(--muted);
        }

        .error-box {
            margin: 16px 0 12px;
            padding: 12px 14px;
            border-radius: 12px;
            background: #fff2f0;
            border: 1px solid #f2c6c2;
            color: #7a2c26;
            font-size: 14px;
        }

        .error-box ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin: 14px 0 6px;
        }

        input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 15px;
            background: #fffdf9;
        }

        input:focus {
            outline: 2px solid #b2e6d6;
            border-color: #b2e6d6;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
            margin: 16px 0 4px;
            color: var(--ink);
        }

        .remember input {
            width: auto;
            margin: 0;
            accent-color: var(--accent);
        }

        .actions {
            margin-top: 18px;
            display: grid;
            gap: 12px;
        }

        button {
            border: none;
            border-radius: 10px;
            padding: 12px 16px;
            background: var(--accent);
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        button:hover {
            background: var(--accent-dark);
        }

        .helper {
            text-align: center;
            font-size: 13px;
            color: var(--muted);
        }

        @keyframes float-in {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Welcome back</h1>
        <p>Log in to manage your shopping lists.</p>
        @if ($errors->any())
            <div class="error-box" role="alert">
                <strong>Login failed.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" name="email" type="email" autocomplete="email" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>

            <label for="remember" class="remember">
                <input id="remember" name="remember" type="checkbox" value="1" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>

            <div class="actions">
                <button type="submit">Sign in</button>
                <div class="helper">Need an account? Ask your admin.</div>
            </div>
        </form>
    </main>
</body>
</html>
