<?php

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

function get_file_target($config_overides, $file_name, $name) {
    $config = include 'config.php';
    $config = array_merge($config, $config_overides);

    $parts = explode('.', $file_name);
    $target = null;
    $first_run = true;
    $files_exist_counter = 0;

    while ($first_run || file_exists($target)) {
        $first_run = false;

        if ($config['enable_random_name']) {
            $target = getcwd() . '/u/' . generateRandomName(end($parts), $config['random_name_length']);
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
        echo implode(",", $config[$fieldname]);
    } else {
        echo $config[$fieldname];
    }
}

function setFieldTrueFalse($fieldname) {
    $config = include 'config.php';

    $field = $config[$fieldname];

    if ($field == "true") {
        // true
        echo '<option value="true" selected >Enabled</option>
          <option value="false" >Disabled</option>';
    } else {
        echo '<option value="true"  >Enabled</option>
          <option value="false" selected>Disabled</option>';
    }
}
