<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Login • WisnuFebri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --primary: #2563eb;
            --primary-soft: #e0e7ff;
            --bg-dark: #020617;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --danger: #dc2626;
            --success: #16a34a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
            background: radial-gradient(circle at top, #1e293b, #020617);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 360px;
            background: var(--card);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, .35);
            animation: fadeUp .6s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            text-align: center;
            margin-bottom: 22px;
        }

        .login-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: var(--text);
        }

        .login-header p {
            margin-top: 6px;
            font-size: 13px;
            color: var(--muted);
        }

        .form-group {
            margin-bottom: 14px;
            position: relative;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 11px 12px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            font-size: 14px;
            background: #f9fafb;
            transition: .2s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px var(--primary-soft);
        }

        /* show / hide password */
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 36px;
            font-size: 13px;
            cursor: pointer;
            color: var(--primary);
            user-select: none;
        }

        .error {
            background: #fef2f2;
            color: var(--danger);
            padding: 10px 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 14px;
        }

        button {
            width: 100%;
            padding: 11px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: .15s ease;
        }

        button:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 12px 24px rgba(37, 99, 235, .35);
        }

        button:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, .4);
            border-top: 2px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .captcha {
            background: #f8fafc;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            margin-bottom: 14px;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .login-footer {
            text-align: center;
            margin-top: 16px;
            font-size: 12px;
            color: var(--muted);
        }
    </style>
</head>

<body>

    <div class="login-card">

        <div class="login-header">
            <h1>Admin Login</h1>
            <p>Secure access to system dashboard</p>
        </div>

        @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" onclick="togglePassword()">Show</span>
            </div>

            {{-- CAPTCHA --}}
            <div class="captcha">
                {{ session('captcha_code') }}
            </div>

            <div class="form-group">
                <label>Captcha</label>
                <input type="text" name="captcha" placeholder="Masukkan kode di atas" required>
            </div>

            <button type="submit" id="loginBtn">
                <span id="btnText">Sign In</span>
                <span class="spinner" id="spinner" style="display:none"></span>
            </button>
        </form>

        <div class="login-footer">
            © {{ date('Y') }} WisnuFebri • Admin Panel
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const toggle = document.querySelector('.toggle-password');
            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = 'Hide';
            } else {
                input.type = 'password';
                toggle.textContent = 'Show';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            document.getElementById('loginBtn').disabled = true;
            document.getElementById('btnText').textContent = 'Signing in...';
            document.getElementById('spinner').style.display = 'inline-block';
        });
    </script>

</body>

</html>