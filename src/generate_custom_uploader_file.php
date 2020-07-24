<?php
    $config = include 'config.php';
    include 'functions.php';
    
    session_start();
    auth_user();

    $result_json = [
        'Name' => "{$config['page_title']}",
        'Version' => get_latest_sharex_version(),
        'DestinationType' => 'ImageUploader, FileUploader, TextUploader',
        'RequestMethod' => 'POST',
        'RequestURL' => join_paths($config['base_url'], 'upload.php'),
        'Body' => 'MultipartFormData',
        'FileFormName' => 'fileupload',
        'Arguments' => [
            'key' => $config['secure_key'],
            'name' => '%h.%mi.%s-%d.%mo.%yy'
        ]
    ];

    header("Content-Disposition: attachment; filename=sharex_{$config['page_title']}_custom_uploader.sxcu");
    header('Content-Type: application/json');
    echo json_encode($result_json);
