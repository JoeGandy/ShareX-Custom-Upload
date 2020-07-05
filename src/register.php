<?php
$config = include 'config.php';
include 'functions.php';

session_start();
auth_user(true);

if ($config['enable_password_login'] === false) {
    header('Location: '.$config['base_url']);
    die();
}

$login_file_path = join_paths(
    getcwd(),
    'login.json'
);

if (file_exists($login_file_path)) {
    header('Location: '.$config['base_url']);
    die();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    unset($_SESSION['message']);
    unset($_SESSION['type']);
    header('Location: '.$config['base_url']);
    die();
}

create_webmanifest();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="css/toggle-bootstrap.min.css">
    <link rel="stylesheet" href="css/toggle-bootstrap-dark.min.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Register - <?php echo $config['page_title']; ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icons/favicon-16x16.png">
    <link rel="manifest" href="manifest.webmanifest">
    <link rel="mask-icon" href="icons/safari-pinned-tab.svg" color="#5c5cbc">
    <link rel="shortcut icon" href="favicon.ico">
    <meta name="msapplication-TileColor" content="#5c5cbc">
    <meta name="msapplication-config" content="icons/browserconfig.xml">
    <meta name="theme-color" content="#5c5cbc">
</head>
<body class="bootstrap">
    <div class="container">
        <br/>
        <h1 class="text-center mt-4 mb-5">Create Account</h1>
        <div class="col-lg-6 offset-lg-3">
            <?php
                if (!empty($_SESSION) && isset($_SESSION['message']) && isset($_SESSION['type'])) {
                    echo display_alert($_SESSION['message'], $_SESSION['type']);
                    unset($_SESSION['message']);
                    unset($_SESSION['type']);
                }
            ?>
            <!-- The children elements all have the bootstrap and bootstrap-dark classes because
            the custom themes currently have a bug in which they will not display validation styles
            if those classes aren't present -->
            <form action="create_account.php" method="POST" id="register-form" novalidate>
                <?php if ($config['enable_username']) {?>
                <div class="form-group bootstrap">
                    <label for="username">Username</label>
                    <input type="text" class="form-control bootstrap" id="username" name="register_username" placeholder="Enter username" required>
                    <div class="invalid-feedback" id="verify-password-feedback">Your username cannot be empty.</div>
                </div>
                <?php } ?>
                <div class="form-group bootstrap">
                    <label for="password">Password</label>
                    <input type="password" class="form-control bootstrap" id="password" name="register_password" placeholder="Enter password" required>
                    <div class="invalid-feedback" id="verify-password-feedback">Your password cannot be empty.</div>
                </div>
                <div class="form-group bootstrap">
                    <label for="password">Confirm Your Password</label>
                    <input type="password" class="form-control bootstrap" id="verify-password" placeholder="Enter password again" required>
                    <div class="invalid-feedback" id="verify-password-feedback">Your passwords do not match.</div>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" name="remember_me" id="remember-me">
                    <label class="form-check-label" for="remember-me">Remember Me</label>
                </div>
                <button type="submit" id="submit-register" class="btn btn-primary btn-block">Create Account</button>
            </form>
        </div>
        <br/>
        <div class="IconButtons d-flex flex-row justify-content-center">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-sun" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="bottom" title="Switch to Light Theme" aria-labelledby="light-title" role="button" tabindex="0">
                <title id="light-title">Switch to Light Theme.</title>
                <path d="M3.5 8a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0z"/>
                <path fill-rule="evenodd" d="M8.202.28a.25.25 0 0 0-.404 0l-.91 1.255a.25.25 0 0 1-.334.067L5.232.79a.25.25 0 0 0-.374.155l-.36 1.508a.25.25 0 0 1-.282.19l-1.532-.245a.25.25 0 0 0-.286.286l.244 1.532a.25.25 0 0 1-.189.282l-1.509.36a.25.25 0 0 0-.154.374l.812 1.322a.25.25 0 0 1-.067.333l-1.256.91a.25.25 0 0 0 0 .405l1.256.91a.25.25 0 0 1 .067.334L.79 10.768a.25.25 0 0 0 .154.374l1.51.36a.25.25 0 0 1 .188.282l-.244 1.532a.25.25 0 0 0 .286.286l1.532-.244a.25.25 0 0 1 .282.189l.36 1.508a.25.25 0 0 0 .374.155l1.322-.812a.25.25 0 0 1 .333.067l.91 1.256a.25.25 0 0 0 .405 0l.91-1.256a.25.25 0 0 1 .334-.067l1.322.812a.25.25 0 0 0 .374-.155l.36-1.508a.25.25 0 0 1 .282-.19l1.532.245a.25.25 0 0 0 .286-.286l-.244-1.532a.25.25 0 0 1 .189-.282l1.508-.36a.25.25 0 0 0 .155-.374l-.812-1.322a.25.25 0 0 1 .067-.333l1.256-.91a.25.25 0 0 0 0-.405l-1.256-.91a.25.25 0 0 1-.067-.334l.812-1.322a.25.25 0 0 0-.155-.374l-1.508-.36a.25.25 0 0 1-.19-.282l.245-1.532a.25.25 0 0 0-.286-.286l-1.532.244a.25.25 0 0 1-.282-.189l-.36-1.508a.25.25 0 0 0-.374-.155l-1.322.812a.25.25 0 0 1-.333-.067L8.203.28zM8 2.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11z"/>
            </svg>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-moon" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="bottom" title=" Switch to Dark Theme" aria-labelledby="dark-title" role="button" tabindex="0">
                <title id="light-title">Switch to Dark Theme.</title>
                <path fill-rule="evenodd" d="M14.53 10.53a7 7 0 0 1-9.058-9.058A7.003 7.003 0 0 0 8 15a7.002 7.002 0 0 0 6.53-4.47z"/>
            </svg>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-clockwise" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="bottom" title="Reset to System Default Theme" aria-labelledby="reset-title" role="button" tabindex="0">
                <title id="reset-title">Reset to System Default Theme.</title>
                <path fill-rule="evenodd" d="M3.17 6.706a5 5 0 0 1 7.103-3.16.5.5 0 1 0 .454-.892A6 6 0 1 0 13.455 5.5a.5.5 0 0 0-.91.417 5 5 0 1 1-9.375.789z"/>
                <path fill-rule="evenodd" d="M8.147.146a.5.5 0 0 1 .707 0l2.5 2.5a.5.5 0 0 1 0 .708l-2.5 2.5a.5.5 0 1 1-.707-.708L10.293 3 8.147.854a.5.5 0 0 1 0-.708z"/>
            </svg>
        </div>
    </div>
    <script
        src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
    <script
        src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
    <script src="js/register.js" type="text/javascript"></script>
    <script src="js/common.js" type="text/javascript"></script>
</body>
</html>
