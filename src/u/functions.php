<?php

// remove setup files if they exists
function rmdir_recursive($dir) {
    foreach (scandir($dir) as $file) {
        if ('.' === $file || '..' === $file)
            continue;
        if (is_dir("$dir/$file"))
            rmdir_recursive("$dir/$file");
        else
            unlink("$dir/$file");
    }
    rmdir($dir);
}
$first = './first/index.php';
if (file_exists($first) && !isset($_GET['removeinstallfiles'])) {
    echo ' <div class="bg-danger text-white p-3">
                <p>Setup files detected!<br>
                this can cause security problems if accessed.
                <strong><a href="?removeinstallfiles" class="text-white">click here to remove the files</a></strong></p>
                <a href="./first" class="btn btn-warning">Want to run setup again?</a> <br><i>this will overwrite existing settings</i>
            </div>';
}
if (isset($_GET['removeinstallfiles'])) {
    rmdir_recursive('./first');
    echo '<div class="alert m-0 rounded-0 alert-success" role="alert">
    Files has been removed
  </div>';
}

// Functions

function displayAlert($text, $type) {
    return '<div class="alert text-center alert-' . $type . '" role="alert">
        <p>' . $text . '</p>
      </div>';
}

function isImage($file) {
    $image_formats = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
    if (!in_array($file, $image_formats))
        return false;

    return true;
}

function generateRandomName($type, $length) {
    $name = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    if (!file_exists(__DIR__ . '/' . $name . '.' . $type)) {
        return $name . '.' . $type;
    } else {
        return generateRandomName($type, $length);
    }
}

function get_file_target($random_name_length, $enable_random_name, $file_name, $name) {


    $parts = explode('.', $file_name);
    $target = null;
    $first_run = true;
    $files_exist_counter = 0;

    while ($first_run || file_exists($target)) {
        $first_run = false;

        if ($enable_random_name) {
            $target = getcwd() . '/u/' . generateRandomName(end($parts), $random_name_length);
        } else {
            if ($files_exist_counter++ < 1) {
                $target = getcwd() . '/u/' . $name . '.' . end($parts);
            } else {
                $target = getcwd() . '/u/' . $name . '_' . $files_exist_counter . '.' . end($parts);
            }
        }
    }
    return $target;
}

function get_latest_sharex_version() {
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: PHP',
            ],
        ],
    ];

    $context = stream_context_create($opts);
    $content = json_decode(file_get_contents('https://api.github.com/repos/ShareX/ShareX/releases/latest', false, $context));
    return str_replace('v', '', $content->tag_name);
}

function bytes_to_string($bytes) {
    $si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    $base = 1024;

    $class = min((int) log($bytes, $base), count($si_prefix) - 1);

    return sprintf('%1.2f', $bytes / pow($base, $class)) . ' ' . $si_prefix[$class];
}

function get_total_free_space_string() {
    return bytes_to_string(disk_free_space('/'));
}

function get_total_space_string() {
    return bytes_to_string(disk_total_space('/'));
}

function auth_user($kill_page_if_fail = true) {
    $config = include 'config.php';

    if (
            !empty($config['allowed_ips']) &&
            !in_array(get_ip(), $config['allowed_ips'])
    ) {
        if ($kill_page_if_fail) {
            die('You are not authed to continue this action, this ip needs to be whitelisted in the config: \'' . get_ip() . '\'');
        } else {
            return false;
        }
    }

    return true;
}

function get_ip() {
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        return $_SERVER["HTTP_CF_CONNECTING_IP"];
    } else {
        if (isset($_SERVER["REMOTE_ADDR"])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return "0.0.0.0";
    }
}

function setFieldContent($fieldname) {
    $config = include 'config.php';

    if ($fieldname == "allowed_ips") {
        if ($config[$fieldname] == "") {
            $config[$fieldname] = "";
            echo "";
        } else {
            echo implode(",", $config[$fieldname]);
        }
    } else {
        echo $config[$fieldname];
    }
}

function setFieldTrueFalse($fieldname) {
    $config = include 'config.php';

    $field = $config[$fieldname];

    if ($field) {
        // true
        echo 'checked';
    }
}

function url() {
    return sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['REQUEST_URI']
    );
}
