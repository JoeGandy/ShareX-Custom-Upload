<?php

include 'u/functions.php';
$config = include 'u/config.php';

$key = $config['secure_key'];
$uploadhost = $config['output_url'];
$redirect = $config['redirect_url'];
$enable_random_name = $config['enable_random_name'];
$random_name_length = $config['random_name_length'];

if ('/robot.txt' === $_SERVER['REQUEST_URI']) {
    die("User-agent: *\nDisallow: /");
}

if (isset($_POST['key'])) {
    if ($_POST['key'] === $key) {
        $target = get_file_target($random_name_length, $enable_random_name, $_FILES['d']['name'], $_POST['name']);

        if (move_uploaded_file($_FILES['d']['tmp_name'], $target)) {
            $target_parts = explode('/u/', $target);
            echo $uploadhost . end($target_parts);
        } else {
            echo 'File upload failed, ensure permissions are writeable on the directory (777), see full config: https://github.com/JoeGandy/ShareX-Custom-Upload/blob/master/README.md#automatic-setup';
        }
    } else {
        echo 'The key provided does not match your config.php, see full config: https://github.com/JoeGandy/ShareX-Custom-Upload/blob/master/README.md#automatic-setup';
    }
} else {
    echo 'You may not upload without the key parameter, see full config: https://github.com/JoeGandy/ShareX-Custom-Upload/blob/master/README.md#automatic-setup';
}