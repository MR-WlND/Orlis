<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập {{ $roleLabel }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 40px;
        }

        .box {
            max-width: 420px;
            margin: 40px auto;
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }

        h2 {
            margin-top: 0;
        }

        .field {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            cursor: pointer;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
        }

        .hint {
            margin-top: 12px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="box">
        <h2>Đăng nhập {{ $roleLabel }}</h2>
        <form method="POST" action="{{ route('role.login.post', ['role' => $role]) }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label for="password">Mật khẩu</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label><input type="checkbox" name="remember" value="1"> Ghi nhớ đăng nhập</label>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
        <div class="hint">Vai trò: {{ $roleLabel }}</div>
    </div>
</body>

</html>
