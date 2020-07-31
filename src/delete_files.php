<?php
$config = include 'merge_config.php';
include 'functions.php';

session_start();
auth_user();

if (isset($_GET['files']) && is_array($_GET['files'])) {
    if (isset($config['enable_delete']) && $config['enable_delete']) {
        $error = false;
        $files = preg_grep('/^([^.])/', $_GET['files']);
        foreach ($files as $filename) {
            $file_path = join_paths(getcwd(), $config['file_storage_folder'], basename($filename));
            if (file_exists($file_path) && basename($file_path) !== '.htaccess') {
                if (!unlink($file_path)) {
                    $error = true;
                }
            } else {
                $error = true;
            }
        }
        if ($error) {
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Files could not be deleted. Please ensure permissions on the upload directory are writable.';
        } else if (count($files) > 1) {
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Files successfully deleted!';
        } else {
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'File successfully deleted!';
        }
        die(header('Location: '.$config['base_url']));
    }
} else {
    if (isset($config['enable_delete_all']) && $config['enable_delete_all']) {
        $dir_path = join_paths(getcwd(), $config['file_storage_folder']);
        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0777, true);
        }
    
        $files = preg_grep('/^([^.])/', scandir($dir_path));
    
        $error = false;
    
        foreach ($files as $key => $file) {
            if ($file !== '.htaccess') {
                $file_path = join_paths(getcwd(), $config['file_storage_folder'], $file);
                if (file_exists($file_path)) {
                    if (!unlink($file_path)) {
                        $error = true;
                    }
                }
            }
        }
    
        if ($error) {
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Uploads could not be cleared. Please ensure permissions on the file and upload directory are writable.';
        } else {
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Uploads successfully cleared.';
        }
        die(header('Location: '.$config['base_url']));
    }    
}
