<?php

function display_alert($text, $type) {
    return '<div class="alert text-center alert-'.$type.'" role="alert">
                <p>'.$text.'</p>
            </div>';
}

function is_image($file) {
    $image_formats = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
    if (!in_array($file, $image_formats))
        return false;

    return true;
}

function generate_random_name($type, $length) {
    $name = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
    return $name.'.'.$type;
}

function join_paths() {
    $segments = func_get_args();

    $protocol_delimiter_pos = strpos($segments[0], '://');
    $protocol_str = '';
    $starting_slash = false;

    if ($segments[0][0] == '/') {
        $starting_slash = true;
    } else if ($protocol_delimiter_pos !== false) {
        $protocol_str = substr($segments[0], 0, $protocol_delimiter_pos + 3);
        $segments[0] = substr($segments[0], $protocol_delimiter_pos + 3);
    }

    $segments_split = [];
    foreach ($segments as $path_segment) {
        $replaced_segment = str_replace('\\', '/', $path_segment);
        $segments_split = array_merge($segments_split, explode('/', $replaced_segment));
    }

    $trimmed_segments = [];
    foreach ($segments_split as $path_segment) {
        $trimmed_path_segment = trim($path_segment, '/');
        if ($trimmed_path_segment !== '') {
            $trimmed_segments[] = $trimmed_path_segment;
        }
    }

    $path_joined = implode('/', $trimmed_segments);

    if ($starting_slash) {
        $path_joined = '/'.$path_joined;
    }

    return $protocol_str.$path_joined;
}

function create_webmanifest() {
    $config = include 'config.php';
    $base_host = parse_url($config['base_url'], PHP_URL_PATH);
    $manifest = [
        'name' => $config['page_title'],
        'description' => 'A image, file, and text uploader',
        'display' => 'standalone',
        'start_url' => $base_host,
        'scope' => $base_host,
        'icons' => [
            [
                'src' => join_paths($base_host, 'icons/android-chrome-192x192.png'),
                'sizes' => '192x192',
                'type' => 'image/png'
            ],
            [
                'src' => join_paths($base_host, 'icons/android-chrome-512x512.png'),
                'sizes' => '512x512',
                'type' => 'image/png'
            ],
            [
                'src' => join_paths($base_host, 'icons/maskable.png'),
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'any maskable'
            ]
        ],
        'theme_color' => '#5c5cbc',
        'background_color' => '#5c5cbc'
    ];
    $manifest_path = join_paths(getcwd(), 'manifest.webmanifest');
    $encoded_manifest = json_encode($manifest);
    if (!file_exists($manifest_path) || file_get_contents($manifest_path) !== $encoded_manifest) {
        file_put_contents($manifest_path, $encoded_manifest);
    }
}

function get_file_target($original_file_name, $new_name) {
    $config = include 'config.php';

    $extension = pathinfo($original_file_name, PATHINFO_EXTENSION);
    $target = null;
    $first_run = true;
    $files_exist_counter = 0;

    if (!isset($extension) || $extension === null || $extension === '') {
        $extension = 'txt';
    }

    // You can't name a file robots.txt, since it's blocked
    // Also add counter to files used by this program
    if ((isset($new_name) && $new_name === 'robots' && $extension === 'txt')
        || file_exists(join_paths(getcwd(), $config['upload_access_path'], $new_name.'.'.$extension))) {
        $files_exist_counter = 1;
    }

    while ($first_run || file_exists($target)) {
        $first_run = false;

        if (!isset($new_name) || $new_name === '' || $new_name === null) {
            $target = join_paths(
                getcwd(),
                $config['file_storage_folder'],
                generate_random_name($extension, $config['random_name_length'])
            );
        } else {
            if ($files_exist_counter++ < 1) {
                $target = join_paths(
                    getcwd(),
                    $config['file_storage_folder'],
                    $new_name.'.'.$extension
                );
            } else {
                $target = join_paths(
                    getcwd(),
                    $config['file_storage_folder'],
                    $new_name.'_'.$files_exist_counter.'.'.$extension
                );
            }
        }
    }

    // Uploading PHP files could potentially be a security risk.
    // With the current system where all accessed files are sent as text/plain,
    // it should be safe, however these lines can be uncommented if necessary.
    /* if ($extension === 'php') {
        $target .= '.txt';
    } */

    return $target;
}

function get_latest_sharex_version() {
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => [
                'User-Agent: PHP'
            ]
        ]
    ];

    $context = stream_context_create($opts);
    $content = json_decode(file_get_contents('https://api.github.com/repos/ShareX/ShareX/releases/latest', false, $context));
    return str_replace('v', '', $content->tag_name);
}

function bytes_to_string($bytes) {
    $si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    $base = 1024;

    $class = min((int) log($bytes, $base), count($si_prefix) - 1);

    return sprintf('%1.2f', $bytes / pow($base, $class)).' '.$si_prefix[$class];
}

function get_total_free_space_string() {
    return bytes_to_string(disk_free_space('/'));
}

function get_total_space_string() {
    return bytes_to_string(disk_total_space('/'));
}

function log_out() {
    session_start();
    session_destroy();

    $login_file_path = join_paths(
        getcwd(),
        'login.json'
    );
    
    if (file_exists($login_file_path)) {
        $login_file = json_decode(file_get_contents($login_file_path), true);

        if ($login_file !== null && isset($_COOKIE['rememberme_authtoken'])) {
            foreach ($login_file['tokens'] as $token => $time_set) {
                if (password_verify($_COOKIE['rememberme_authtoken'], $token)) {
                    unset($login_file['tokens'][$token]);
                }
            }
            file_put_contents($login_file_path, json_encode($login_file));
        }
    }

    setcookie('rememberme_authtoken', '', time() - 3600);
}

function show_error_page($message) {
    $error_msg = $message;
    include 'error.php';
    die();
}

function auth_user($ip_only=false){
    $config = include 'config.php';
    
    if(
        !empty($config['allowed_ips']) && 
        !in_array(get_ip(), $config['allowed_ips'])
    ) {
        show_error_page('This IP is not authorized to view this page. If you are the owner of this site, add the following IP address to the allowlist in your config: "'.get_ip().'"');
    }
    if (!$ip_only && isset($config['enable_password_login']) && $config['enable_password_login']) {
        $login_file_path = join_paths(
            getcwd(),
            'login.json'
        );
        
        if (!file_exists($login_file_path)) {
            log_out();
            header('Location: '.join_paths($config['base_url'], 'register'));
            die();
        }
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            header('Location: '.join_paths($config['base_url'], 'login'));
            die();
        }
    }
    return true;
}

function get_ip() {
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        return $_SERVER["HTTP_CF_CONNECTING_IP"];
    } else{
        if(isset($_SERVER["REMOTE_ADDR"])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return "0.0.0.0";
    }
}

/* https://stackoverflow.com/a/20537130/8005366 */
function count_lines_in_file($file_path) {
    $f = fopen($file_path, 'rb');
    $lines = 0;

    while (!feof($f)) {
        $file_buffer = fread($f, 8192);
        $lines += substr_count($file_buffer, "\n");
    }

    fclose($f);

    return $lines + (strlen($file_buffer) ? 1 : 0);
}
