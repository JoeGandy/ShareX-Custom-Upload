<?php

include 'functions.php';
$config = include 'merge_config.php';

session_start();
auth_user($config);

if (!isset($config['enable_updater']) || !$config['enable_updater']) {
    header('Location: '.$config['base_url']);
    die();
}

$opts = [
    'http' => [
        'method' => 'GET',
        'header' => [
            'User-Agent: PHP'
        ]
    ]
];

$context = stream_context_create($opts);
$content = json_decode(file_get_contents('https://api.github.com/repos/JoeGandy/ShareX-Custom-Upload/releases/latest', false, $context));

$download_path = join_paths(getcwd(), 'latest-release.zip');

$ch = curl_init($content->assets[0]->browser_download_url);

$file = fopen($download_path, 'wb');

curl_setopt($ch, CURLOPT_FILE, $file);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

curl_exec($ch);

curl_close($ch);

fclose($file);

$zip = new ZipArchive();

if ($zip->open($download_path, ZIPARCHIVE::RDONLY) !== TRUE) {
    $_SESSION['type'] = 'danger';
    $_SESSION['message'] = 'An error occurred while opening the update ZIP archive.';
    die(header('Location: '.$config['base_url']));
}

$zip->extractTo(getcwd());
$zip->close();

die(header('Location: '.join_paths($config['base_url'], 'release/update.php')));

?>
