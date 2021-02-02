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
    <h1 class="h3 mb-3 fw-normal">Регистрация в системе ТОиР</h1>
    <form action="auth.php" method="POST">
        <input type="hidden" name="action" value="goReg">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Имя" required autofocus>
        </div>
        <div class="mb-3">
            <input type="text" name="last_name" class="form-control" placeholder="Фамилия" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password1" class="form-control" placeholder="Пароль" required>
        </div>
        <div class="mb-3">
            <input type="password" name="password2" class="form-control" placeholder="Пароль еще раз" required>
        </div>
        <?php if ($_SESSION['reg_error']) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['reg_error']; unset($_SESSION['reg_error']);?>
            </div>
        <?php } ?>
        <button class="btn btn-lg btn-primary" type="submit">Войти</button>
    </form>
    <div class="row mt-4">
        <div class="col text-end pe-4">
            <a href="auth.php?action=reg">Регистрация</a>
        </div>
        <div class="col text-start ps-4">
            <a href="#">Забыли пароль?</a>
        </div>
    </div>
</div>   
</body>
</html>
