<?php
$config = include 'merge_config.php';
include 'functions.php';

session_start();
auth_user($config);

ini_set("memory_limit", "-1");
set_time_limit(0);

$dir_path = join_paths(getcwd(), $config['file_storage_folder']);
if (!file_exists($dir_path)) {
    mkdir($dir_path, 0777, true);
}

if (isset($_GET['files']) && is_array($_GET['files'])) {
    $files = preg_grep('/^([^.])/', $_GET['files']);
} else if (isset($config['enable_zip_dump']) && $config['enable_zip_dump']) {
    $files = preg_grep('/^([^.])/', scandir($dir_path));
} else {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'The ZIP feature is not enabled in your configuration.';
    die(header('Location: '.$config['base_url']));
}

date_default_timezone_set('UTC');

$zip_name = 'custom_uploader_backup_'.date('d-m-Y_H-i-s').'.zip';
$zip_path = join_paths(getcwd(), $config['zip_storage_folder'], $zip_name);

$zip_dir_path = join_paths(getcwd(), $config['zip_storage_folder']);
if (!file_exists($zip_dir_path)) {
    mkdir($zip_dir_path, 0777, true);
}

$zip = new ZipArchive();
$empty = true;

if ($zip->open($zip_path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'An error occurred while creating your ZIP file.';
    die(header('Location: '.$config['base_url']));
}

foreach($files as $file) {
    $file_path = join_paths(getcwd(), $config['file_storage_folder'], basename($file));
    if(!is_dir($file_path) && 'php' !== pathinfo($file_path, PATHINFO_EXTENSION)
        && 'zip' !== pathinfo($file_path, PATHINFO_EXTENSION)){
        $zip->addFile($file_path, $file);
        $empty = false;
    }
}

$zip->close();

if ($empty) {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'You have not uploaded any files.';
    die(header('Location: '.$config['base_url']));
}

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename='.$zip_name);
header('Content-Length: ' . filesize($zip_path));

readfile($zip_path);

