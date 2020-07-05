<?php

$config = include 'config.php';
include 'functions.php';

session_start();
auth_user(true);

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    unset($_SESSION['message']);
    unset($_SESSION['type']);
    header('Location: '.$config['base_url']);
    die();
}

$login_file_path = join_paths(
    getcwd(),
    'login.json',
);

if (!file_exists($login_file_path)) {
    log_out();
    header('Location: '.join_paths($config['base_url'], 'register'));
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login_password'])) {
        $login_file = json_decode(file_get_contents($login_file_path), true);

        if ($login_file === null) {
            die();
        }

        if (isset($config['enable_username']) && $config['enable_username']) {
            if (isset($_POST['login_username']) && $_POST['login_username'] === $login_file['username'] && password_verify($_POST['login_password'], $login_file['password'])) {
                $_SESSION['logged_in'] = true;
                if (isset($_POST['remember_me']) && $_POST['remember_me'] === 'on') {
                    $token_length = 64;
                    $authentication_token = bin2hex(random_bytes($token_length));
                    $login_file['tokens'][password_hash($authentication_token, PASSWORD_DEFAULT)] = time();
                    file_put_contents($login_file_path, json_encode($login_file));
                    setcookie('rememberme_authtoken', $authentication_token, time() + 86400*$config['remember_me_expiration_days']);
                }
            } else {
                $_SESSION['message'] = 'The username or password you have entered is incorrect.';
                $_SESSION['type'] = 'danger';
            }
        } else {
            if (password_verify($_POST['login_password'], $login_file['password'])) {
                $_SESSION['logged_in'] = true;
                if (isset($_POST['remember_me']) && $_POST['remember_me'] === 'on') {
                    $token_length = 64;
                    $authentication_token = bin2hex(random_bytes($token_length));
                    $login_file['tokens'][password_hash($authentication_token, PASSWORD_DEFAULT)] = time();
                    file_put_contents($login_file_path, json_encode($login_file));
                    setcookie('rememberme_authtoken', $authentication_token, time() + 86400*$config['remember_me_expiration_days']);
                }
            } else {
                $_SESSION['message'] = 'The password you have entered is incorrect.';
                $_SESSION['type'] = 'danger';
            }
        }
        header('Location: '.join_paths($config['base_url'], 'login'));
        die();
    }
} else {
    header('Location: '.join_paths($config['base_url'], 'login'));
    die();
}
