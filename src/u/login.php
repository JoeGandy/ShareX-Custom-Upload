<?php
session_start();
$config = include 'config.php';

if ($config['username'] == "" || $config['password'] == "" || $config['secure_key'] == "") {
    header('Location: ./first');
}

if (isset($_POST['submit'])) {


    $username = $_POST['username'];
    $password = sha1($_POST['password']);

    if ($username == $config['username'] AND $password == $config['password']) {
        
        $_SESSION['AUTH_ID'] = 34234;

        header('Location: index.php');

    } else {
        $error = "Username and Password doesn't match";
    }
}

if (isset($_GET['logout'])) {
    $success = "You have been logged out";
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Login</title>
        <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body class="d-flex justify-content-center align-items-center">
        <form class="form-signin" method="post">

            <h1 class="h4 mb-3 font-weight-normal">Please sign in</h1>
            <?php
            if (isset($error)) {

                echo '<div class="alert alert-danger" role="alert">
            ' . $error . '
          </div>';
            }
            if (isset($success)) {

                echo '<div class="alert alert-success" role="alert">
            ' . $success . '
          </div>';
            }
            ?>
            <label for="inputEmail" class="sr-only">Username</label>
            <input name="username" type="text" id="inputEmail" class="form-control" placeholder="Username"  autofocus="" autocomplete="off" >
            <label for="inputPassword" class="sr-only">Password</label>
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password"  autocomplete="off" >

            <button class="btn btn-lg btn-primary btn-block" type="submit" name="submit">Sign in</button>

        </form>
    </body>
</html>