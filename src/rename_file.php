<?php
$config = include 'merge_config.php';
include 'functions.php';

session_start();
auth_user();

if (isset($config['enable_rename']) && $config['enable_rename']) {
    if (isset($_GET['oldname']) && isset($_GET['newname'])) {
        $old_path = join_paths(getcwd(), $config['file_storage_folder'], basename($_GET['oldname']));

        if (file_exists($old_path) && preg_match('/^([^.])/', $_GET['oldname']) === 1) {
            if (isset($_GET['newname'])) {
                $newfile_basename = basename($_GET['newname']);
            } else {
                $newfile_basename = '';
            }
            
            $newfile_pathinfo = pathinfo($newfile_basename);
            $extension_exists = isset($newfile_pathinfo['extension']) && $newfile_pathinfo['extension'] !== '';
            if ($extension_exists) {
                $target = get_file_target($newfile_basename, $newfile_pathinfo['filename']);
            } else {
                $target = get_file_target(basename($old_path), $newfile_pathinfo['filename']);
            }

            if (rename($old_path, $target)) {
                $_SESSION['type'] = 'success';
                $_SESSION['message'] = 'You have successfully renamed <strong>' . basename($old_path) . '</strong> to <strong>' . basename($target) . '</strong>';
            } else {
                $_SESSION['type'] = 'danger';
                $_SESSION['message'] = 'Error renaming file. Please ensure permissions are writable on the upload directory and that your file name is valid.';
            }
            die(header('Location: '.$config['base_url']));
        } else {
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Original file does not exist!';
            die(header('Location: '.$config['base_url']));
        }
    }
}
