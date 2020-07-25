<?php

if (!file_exists(dirname(__FILE__, 2).'/'.'functions.php')) {
    // If this isn't running in a release folder, we can just assume this is the normal uploader and load config
    $config = include 'config.php';

    header('Location: '.$config['base_url']);
    die();
}

include './functions.php';

session_start();
auth_user();

// This should include all files except .htaccess, config.php, and this file (update.php)
$UPDATE_FILES = [
    'css/main.css',
    'css/solarized-dark.css',
    'css/solarized-light.css',
    'css/text-viewer.css',
    'css/toggle-bootstrap.min.css',
    'css/toggle-bootstrap-dark.min.css',
    'icons/android-chrome-192x192.png',
    'icons/android-chrome-512x512.png',
    'icons/apple-touch-icon.png',
    'icons/browserconfig.xml',
    'icons/favicon-16x16.png',
    'icons/favicon-32x32.png',
    'icons/maskable.png',
    'icons/mstile-150x150.png',
    'icons/safari-pinned-tab.svg',
    'js/common.js',
    'js/dataTableDateTimeRender.js',
    'js/main.js',
    'js/register.js',
    'js/textViewer.js',
    'create_account.php',
    'delete_files.php',
    'error.php',
    'favicon.ico',
    'functions.php',
    'generate_custom_uploader_file.php',
    'generate_shell_uploader.php',
    'generate_zip_of_files.php',
    'index.php',
    'login.php',
    'logout.php',
    'register.php',
    'rename_file.php',
    'rewrite.php',
    'service-worker.js',
    'text_viewer.php',
    'upload.php',
    'upload_text.php',
    'verify_login.php',
    'VERSION'
];

// Copy these files if they don't exist already
$OPTIONAL_FILES = [
    '.htaccess',
    'config.php'
];

foreach ($UPDATE_FILES as $file) {
    $source_file = join_paths(dirname(__FILE__, 1), $file);
    $dest_file = join_paths(dirname(__FILE__, 2), $file);

    $dest_dir = dirname($dest_file, 1);
    if (!file_exists($dest_dir)) {
        mkdir($dest_dir, 0777, true);
    }

    if (!copy($source_file, $dest_file)) {
        $_SESSION['type'] = 'danger';
        $_SESSION['message'] = 'Update failed. Please ensure that the directory where your uploader is installed ('.dirname(__FILE__, 2).') is writable (777).';
    } else {
        $_SESSION['type'] = 'success';
        $_SESSION['message'] = 'Update successful! You can now delete the release folder.';
    }
}

foreach ($OPTIONAL_FILES as $file) {
    $dest_file = join_paths(dirname(__FILE__, 2), $file);

    if (!file_exists($dest_file)) {
        $source_file = join_paths(dirname(__FILE__, 1), $file);

        $dest_dir = dirname($dest_file, 1);
        if (!file_exists($dest_dir)) {
            mkdir($dest_dir, 0777, true);
        }

        if (!copy($source_file, $dest_file)) {
            $_SESSION['type'] = 'danger';
            $_SESSION['message'] = 'Update failed. Please ensure that the directory where your uploader is installed ('.dirname(__FILE__, 2).') is writable (777).';
        } else {
            $_SESSION['type'] = 'success';
            $_SESSION['message'] = 'Update successful! You can now delete the release folder.';
        }
    }
}

$config = include '../config.php';

header('Location: '.$config['base_url']);
die();

?>
