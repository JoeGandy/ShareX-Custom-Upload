<?php

$config = include 'config.php';
include 'functions.php';

$login_file_path = join_paths(
    getcwd(),
    'login.json'
);

if (file_exists($login_file_path)) {
    header('Location: '.$config['base_url']);
    die();
}

session_start();
auth_user(true);

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    unset($_SESSION['message']);
    unset($_SESSION['type']);
    header('Location: '.$config['base_url']);
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register_password'])) {
        $username = $_POST['register_username'];
        if ($config['enable_username'] === true) {
            $username = '';
        }
        if (($_POST['register_username'] === '' && $config['enable_username']) || $_POST['register_password'] === '') {
            $_SESSION['message'] = 'Username and password cannot be empty.';
            $_SESSION['type'] = 'danger';
        } else {
            $login_file = [
                'username' => $_POST['register_username'],
                'password' => password_hash($_POST['register_password'], PASSWORD_DEFAULT),
                'tokens' => []
            ];

            if (isset($_POST['remember_me']) && $_POST['remember_me'] === 'on') {
                $token_length = 64;
                $authentication_token = bin2hex(random_bytes($token_length));
                $login_file['tokens'][password_hash($authentication_token, PASSWORD_DEFAULT)] = time();
            }
    
            if (file_put_contents($login_file_path, json_encode($login_file))) {
                $_SESSION['logged_in'] = true;
                if (isset($_POST['remember_me']) && $_POST['remember_me'] === 'on') {
                    setcookie('rememberme_authtoken', $authentication_token, time() + 86400*$config['remember_me_expiration_days']);
                }
            } else {
                $_SESSION['message'] = 'Error saving username and password.';
                $_SESSION['type'] = 'danger';
            }
        }
        header('Location: '.join_paths($config['base_url'], 'register'));
        die();
    }
} else {
    header('Location: '.$config['base_url']);
    die();
}
