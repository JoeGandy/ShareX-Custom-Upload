<?php

return [
	/* This is a secure key that only you should know, an added layer of security for the image upload */
    'secure_key' => 'somerandomlongstringoftextforkey',

    /* This is the url your output will be, usually http://www.domain.com/u/, also going to this url will be the gallery page */
    'output_url' => 'http://example.com/u/',

    /* This request url, so the path pointing to the uplaod.php file */
    'request_url' => 'http://example.com/upload.php',

    /* This is a redirect url if the script is accessed directly */
    'redirect_url' => 'http://example.com/',

    /* This is a list of IPs that can access the gallery page (Leave empty for universal access) */
    'allowed_ips' => ['127.0.0.1', '::1', '0.0.0.0'],

    /* Page title of the gallery page */
    'page_title' => 'My Upload Site',

    /* Heading text at the top of the gallery page */
    'heading_text' => 'Uploading Site',

    /* Delete file option (true to enable, disabled by default) */
    'enable_delete' => false,

    /* Show image in tooltip  (true to enable, disabled by default) */
    'enable_tooltip' => false,

    /* Generate random name (true to enable, disabled by default) */
    'enable_random_name' => false,

    /* Select lenght of random name (10 symbols by default) */
    'random_name_length' => 10,

];
