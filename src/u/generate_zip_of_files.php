<?php

$config = include 'config.php';
include 'functions.php';

auth_user();

if (isset($config['enable_zip_dump']) && $config['enable_zip_dump']) {
    ini_set("memory_limit", "-1");
    set_time_limit(0);

    $files1 = preg_grep('/^([^.])/', scandir('.'));
    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    $zipname = 'backups/custom_uploader_backup' . date('d-m-Y_H-i-s') . '.zip';
    $zip = new ZipArchive();
    $empty = true;

    if ($zip->open($zipname, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
        die("An error occurred creating your ZIP file.");
    }

    foreach ($files1 as $key => $file) {
        if (!is_dir($file) && 'php' !== pathinfo($file, PATHINFO_EXTENSION) && 'zip' !== pathinfo($file, PATHINFO_EXTENSION)) {
            $zip->addFile($file);
            $empty = false;
        }
    }

    $zip->close();

    if ($empty) {
        die('Your zip file has nothing that can be added, so canceling.');
    }

    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename=' . $zipname);
    header('Content-Length: ' . filesize($zipname));

    readfile($zipname);
} else {
    die('Feature not enabled.');
}