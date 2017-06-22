<?php

return array(
	/* This is a secure key that only you should know, an added layer of security for the image upload */
    'secure_key' => 'somerandomlongstringoftextforkey',

    /* This is the url your output will be, usually http://www.domain.com/u/, also going to this url will be the gallery page */
    'output_url' => 'http://example.com/u/data',

    /* This is a redirect url if the script is accessed directly */
    'redirect_url' => 'http://example.com/',

    /* This is a list of IPs that can access the gallery page (Leave empty for universal access) */
    'allowed_ips' => array('192.168.0.0', '0.0.0.0'),

    /* Page title of the gallery page */
    'page_title' => 'My Upload Site',

    /* Heading text at the top of the gallery page */
    'heading_text' => 'Uploading Site',
);
