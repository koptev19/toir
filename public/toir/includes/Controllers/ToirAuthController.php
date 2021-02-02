<?php

class ToirAuthController extends ToirController
{

    public function index()
    {
        $this->view('auth/index');
    }

    public function go()
    {
        $user = UserToir::filter([
            'email' => $_REQUEST['email'],
            'password' => $_REQUEST['password'],
        ])->first();

        if($user) {
            if($user->connected) {
                $_SESSION['auth_id'] = $user->id;
                header('Location: main.php');
            } else {
                $_SESSION['auth_error'] = 'Вы временно отключены от системы';
                header('Location: auth.php');
            }
        } else {
            $_SESSION['auth_error'] = 'Пользователь с введёнными данными не обнаружен в системе';
            header('Location: auth.php');
        }
    }

    public function logout()
    {
        unset($_SESSION['auth_id']);
        $_SESSION['auth_error'] = 'Вы успешно вышли из системы';
        header('Location: auth.php');
    }

    public function reg()
    {
        $this->view('auth/reg');
    }

    public function goReg()
    {
        if($this->validateReg()) {
            UserToir::create([
                'name' => trim($_REQUEST['name']),
                'last_name' => trim($_REQUEST['last_name']),
                'email' => trim($_REQUEST['email']),
                'password' => trim($_REQUEST['password1']),
            ]);
            header('Location: auth.php?action=reg_ok');
        } else {
            header('Location: auth.php?action=reg');
        }
    }

    public function reg_ok()
    {
        $this->view('auth/reg_ok');
    }

    private function validateReg(): bool
    {
        $error = '';

        if(empty($_REQUEST['name'])) {
            $error = 'Введите имя';
        }
        if(empty($_REQUEST['last_name'])) {
            $error = 'Введите фамилию';
        }
        if(empty($_REQUEST['email'])) {
            $error = 'Введите email';
        }
        if(empty($_REQUEST['password1'])) {
            $error = 'Введите пароль';
        }
        if($_REQUEST['password1'] != $_REQUEST['password2']) {
            $error = 'Пароли не совпадают';
        }

        if($error) {
            $_SESSION['reg_error'] = $error;
            return false;
        } else {
            return true;
        }
    }


}