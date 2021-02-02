<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Авторизация в системе ТОиР</title>

    <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <style>
        html, body {
            height: 100%;
        }

        .form-auth {
            max-width: 510px;
        }
        .form-auth input {
            max-width: 250px;
            margin: auto;
        }
    </style>

    
</head>
<body class="text-center d-flex">
<div class="form-auth w-100 m-auto">
    <h1 class="h3 mb-3 fw-normal">Авторизация в системе ТОиР</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="action" value="go">
        <div class="mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
        </div>
        @error('email')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror
        @error('password')
            <div class="alert alert-danger" role="alert">
                {{ $message }}
            </div>
        @enderror
        <div class="mb-3 mx-auto">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Запомнить меня</label>
        </div>
        <button class="btn btn-primary" type="submit">Войти</button>
    </form>
    <div class="row mt-4">
        <div class="col text-end pe-4">
            <a href="#">Регистрация</a>
        </div>
        <div class="col text-start ps-4">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Забыли пароль?</a>
            @endif
        </div>
    </div>
</div>   
</body>
</html>