<?php
    $config = include 'config.php';
    include 'functions.php';
    
    auth_or_kill();

    $result_json = [
        'Version' => get_latest_sharex_version(),
        'RequestMethod' => 'POST',
        'RequestURL' => $config['request_url'],
        'Body' => 'MultipartFormData',
        'FileFormName' => 'd',
        'Arguments' => [
            'key' => $config['secure_key'],
            'name' => '%h.%mi.%s-%d.%mo.%yy',
        ],
    ];

    header('Content-disposition: attachment; filename=sharex_custom_uploader_import_file.sxcu');
    header('Content-Type: application/json');
    echo json_encode($result_json);
