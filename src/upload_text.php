<?php

include 'functions.php';
$config = include 'config.php';

session_start();

if (empty($_POST) &&
        isset($_SERVER['REQUEST_METHOD']) &&
        strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'Upload error: your file is larger than the post_max_size set in your php.ini.';
    echo 'Upload error: your file is larger than the post_max_size set in your php.ini.';
    die();
}

if (isset($_POST['key'])) {
    if ($_POST['key'] === $config['secure_key']) {
        if (isset($_POST['filename'])) {
            $file_basename = basename($_POST['filename']);
        } else {
            $file_basename = '';
        }

        if (!isset($_POST['textcontent']) || $_POST['textcontent'] === '') {
            echo 'File upload failed: file content cannot be empty.';
            die();
        }

        $filename_no_extension = pathinfo($file_basename, PATHINFO_FILENAME);

        $target = get_file_target($file_basename, false, $filename_no_extension);

        $dir_path = join_paths(
            getcwd(),
            $config['file_storage_folder']
        );

        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0777, true);
        }

        if (file_put_contents($target, $_POST['textcontent'])) {
            echo join_paths($config['base_url'], $config['upload_access_path'], basename($target));
        } else {
            echo 'File upload failed, please ensure permissions are writeable (777) on the upload directory ('.$dir_path.').';
        }
    } else {
        echo 'The key provided does not match the secure_key set in your config.php.';
    }
} else if (auth_user()) {
    if (isset($_POST['filename'])) {
        $file_basename = basename($_POST['filename']);
    } else {
        $file_basename = '';
    }

    if (!isset($_POST['textcontent']) || $_POST['textcontent'] === '') {
        $_SESSION['message'] = 'File upload failed: file content cannot be empty.';
        $_SESSION['type'] = 'danger';
        header('Location: '.$config['base_url']);
        die();
    }

    $filename_no_extension = pathinfo($file_basename, PATHINFO_FILENAME);

    $target = get_file_target($file_basename, false, $filename_no_extension);

    $dir_path = join_paths(
        getcwd(),
        $config['file_storage_folder']
    );

    if (!file_exists($dir_path)) {
        mkdir($dir_path, 0777, true);
    }

    if (file_put_contents($target, $_POST['textcontent'])) {
        $_SESSION['message'] = 'Text upload successful!';
        $_SESSION['type'] = 'success';
        header('Location: '.$config['base_url']);
    } else {
        $_SESSION['message'] = 'File upload failed, please ensure permissions are writeable (777) on the upload directory ('.$dir_path.')';
        $_SESSION['type'] = 'danger';
        header('Location: '.$config['base_url']);
    }
} else {
    echo 'You may not upload without the key parameter. Check your ShareX configuration or try redownloading the ShareX config file from your gallery page.';
}
