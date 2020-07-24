<?php

include 'functions.php';
$config = include 'config.php';

session_start();

if (empty($_FILES) && empty($_POST) &&
        isset($_SERVER['REQUEST_METHOD']) &&
        strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'Upload error: your file is larger than the post_max_size set in your php.ini.';
    echo 'Upload error: your file is larger than the post_max_size set in your php.ini.';
    die();
} else if ($_FILES['fileupload']['error'] === 1) {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'Upload error: your file is larger than the upload_max_filesize set in your php.ini.';
    echo 'Upload error: your file is larger than the upload_max_filesize set in your php.ini.';
    die();
}

if (isset($_POST['key'])) {
    if ($_POST['key'] === $config['secure_key']) {
        $filename = pathinfo($_FILES['fileupload']['name'], PATHINFO_FILENAME);

        $target = get_file_target($_FILES['fileupload']['name'], $config['use_default_naming_scheme_for_sharex'], $filename);
        $dir_path = join_paths(
            getcwd(),
            $config['file_storage_folder']
        );

        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0777, true);
        }

        if (move_uploaded_file($_FILES['fileupload']['tmp_name'], $target)) {
            echo join_paths($config['base_url'], $config['upload_access_path'], basename($target));
        } else {
            echo 'File upload failed, please ensure permissions are writeable (777) on the upload directory ('.$dir_path.')';
        }
    } else {
        echo 'The key provided does not match the secure_key set in your config.php.';
    }
} else if (auth_user()) {
    if (!isset($_FILES['fileupload'])) {
        header('Location: '.$config['base_url']);
        die();
    }

    $filename = pathinfo($_FILES['fileupload']['name'], PATHINFO_FILENAME);

    $target = get_file_target($_FILES['fileupload']['name'], $config['use_default_naming_scheme_for_gallery'], $filename);
    $dir_path = join_paths(
        getcwd(),
        $config['file_storage_folder']
    );

    if (!file_exists($dir_path)) {
        mkdir($dir_path, 0777, true);
    }

    if (move_uploaded_file($_FILES['fileupload']['tmp_name'], $target)) {
        $_SESSION['message'] = 'File upload successful!';
        $_SESSION['type'] = 'success';
    } else {
        $_SESSION['message'] = 'File upload failed, ensure permissions are writeable (777) on the upload directory ('.$dir_path.')';
        $_SESSION['type'] = 'danger';
    }
} else {
    echo 'You may not upload without the key parameter. Check your ShareX configuration or try redownloading the ShareX config file from your gallery page.';
}
