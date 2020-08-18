<?php

$req_noquery = urldecode(strtok($_SERVER['REQUEST_URI'], '?#'));
$file_name = basename($req_noquery);

// Disable automatic site crawling by search engines
if ('robots.txt' === $file_name) {
    die("User-agent: *\nDisallow: /");
}

// URL prettifying for login and register
if ('login' === $file_name) {
    include 'login.php';
    die();
}

if ('register' === $file_name) {
    include 'register.php';
    die();
}

// These have to be here because prettifying urls will reinclude functions.php
$config = include 'merge_config.php';
include 'functions.php';

if (strpos($_SERVER['REQUEST_URI'], '/css/')) {
    header('Location: '.join_paths($config['base_url'], substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '/css/'))));
    die();
}

if (strpos($_SERVER['REQUEST_URI'], '/js/')) {
    header('Location: '.join_paths($config['base_url'], substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '/js/'))));
    die();
}

if (strpos($_SERVER['REQUEST_URI'], '/icons/')) {
    header('Location: '.join_paths($config['base_url'], substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '/icons/'))));
    die();
}

if ('manifest.webmanifest' === $file_name) {
    header('Location: '.join_paths($config['base_url'], 'manifest.webmanifest'));
    die();
}

if ('favicon.ico' === $file_name) {
    header('Location: '.join_paths($config['base_url'], 'favicon.ico'));
    die();
}

$full_url = join_paths($_SERVER['HTTP_HOST'], $req_noquery);
$desired_url = join_paths($config['base_url'], $config['upload_access_path'], $file_name);

$protocol_delimiter_pos = strpos($desired_url, '://');
if ($protocol_delimiter_pos !== false) {
    $desired_url = substr($desired_url, $protocol_delimiter_pos + 3);
}

if ($full_url !== $desired_url) {
    http_response_code(403);
    show_error_page('Error 403: Access Forbidden!<br>You do not have permission to access this page.');
}

$file_path = join_paths(getcwd(), $config['file_storage_folder'], $file_name);

if (file_exists($file_path)) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file_path);
    finfo_close($finfo);

    if ((substr($mime_type, 0, 4) === 'text' || $mime_type === 'application/json')
        && !(isset($_GET['raw']) && strtolower($_GET['raw']) === 'true' )
        && $config['enable_rich_text_viewer']) {
        $file_text = file_get_contents($file_path);
        $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
        if ($file_extension === 'txt') {
            $file_extension = '';
        }
        $line_count = count_lines_in_file($file_path);
        $disable_highlight = filesize($file_path) > 100000;
        include 'text_viewer.php';
        die();
    } else {
        header('Content-Type: '.$mime_type);
        header('Content-Length: ' . filesize($file_path));

        if (isset($config['enable_image_cache']) && $config['enable_image_cache']) {
            $cache_ttl_seconds = 900; // 15 minutes
            $cache_revalidate_ttl_seconds = 900; // 15 minutes

            header("Cache-Control: public, max-age={$cache_ttl_seconds}, stale-while-revalidate={$cache_revalidate_ttl_seconds}");

            $etag = '';
            $etag_algo_used = '';

            $time_start = microtime(true); 

            if (PHP_INT_SIZE === 8) {
                // 64-bit, so we use SHA512 for faster performance
                $etag = hash_file('sha512', $file_path);
                $etag_algo_used = 'SHA-512';
            } else {
                // 32-bit (or other?), so we use SHA256 for faster performance
                $etag = hash_file('sha256', $file_path);
                $etag_algo_used = 'SHA-256';
            }

            header('ETag: "'.$etag.'"');

            if (isset($config['debug_mode']) && $config['debug_mode']) {
                $time_diff = round((microtime(true) - $time_start) * 1000, 4);
                header('X-ETag-Generation-Milliseconds: '.$time_diff);
            }

            header('X-ETag-Algorithm: '.$etag_algo_used);
        }

        readfile($file_path);
    }
} else {
    http_response_code(404);
    show_error_page('Error 404: Not Found!<br>Please ensure you have the current URL.');
}
