<?php

return [
    /* This sets the url where your gallery page will be accessible
     * If you set this to https://www.example.com/ your gallery page will be accessible there.
     */
    'base_url' => 'https://www.example.com/',

    /* This is a secure key that only you should know, an added layer of security for the image upload
     * You can just set this to some really long random string, since you don't need to remember it.
     * But please don't leave it as the default.
     */
    'secure_key' => 'super secret key',

    /* This is the folder where your uploaded files will be stored.
     * UNLESS YOU HAVE SPECIFIC CIRCUMSTANCES, YOU SHOULDN'T CHANGE THIS.
     * Instead, change the upload_access_path.
     * If you must change this, change the path of the `Directory` tag in `.htaccess` from `u/` to your new folder
     * Do not set this to '/'. Your site will either not work, or people will be able to execute arbitrary code on your computer.
     */
    'file_storage_folder' => 'u/',

    /* Sets the web path where you will be able to access your files 
     * If you set this to / and your base_url is https://www.example.com, your files will be accessible from
     * https://www.example.com/filename.ext
     * If you set this to myfiles/, your files will be accessible at https://www.example.com/myfiles/filename.ext
     * For security reasons, this cannot be equal to your file_storage_folder.
     */
    'upload_access_path' => '/',

    /* This is the folder where your zip dumps will be stored if the feature is enabled. */
    'zip_storage_folder' => 'backups/',

    /* This is a list of IPs that can access the gallery page (Leave empty to disable IP blocking) */
    'allowed_ips' => ['127.0.0.1', '::1'],

    /* This enables or disables password protection for your gallery page. If this is off, only IP verification will be used */
    'enable_password_login' => false,

    /* This sets whether you need to provide a username to log in. */
    'enable_username' => true,

    /* This sets how long remember me tokens will be valid for. You don't need to change this. */
    'remember_me_expiration_days' => 30,

    /* Page title shown in the browser for the gallery page */
    'page_title' => 'My File Uploader',

    /* Heading text at the top of the gallery page */
    'heading_text' => 'My File Uploader',

    /* Choose which date format to use to display dates on the gallery page */
    /* See available options at https://momentjs.com/docs/#/displaying/format/ */
    'gallery_date_format' => 'MMMM Do YYYY, HH:mm:ss',

    /* This sets whether you will be able to upload files and text from your gallery page */
    'enable_gallery_page_uploads' => true,

    /* This sets whether you will be able to delete all of your uploads at once */
    'enable_delete_all' => true,

    /* Delete file option (true to enable, disabled by default) */
    'enable_delete' => true,

    /* Enables the option to rename files from the gallery page */
    'enable_rename' => true,

    /* Show image in tooltip  (true to enable, disabled by default) */
    'enable_tooltip' => true,

    /* Show link to download all files as .zip (Untested with large archives of files) */
    'enable_zip_dump' => false,

    /* Enable bulk download and delete on the gallery page */
    'enable_bulk_operations' => true,

    /* This sets whether to enable the formatted text viewer or to send all text files as raw text by default */
    'enable_rich_text_viewer' => true,

    /* This sets the naming scheme to use for ShareX uploads.
     * Possible values are: 
     *     keep, provided, date, and random
     * keep mode will keep the file name sent by ShareX
     * provided mode will use the name sent in the name parameter. 
     *     See the ShareX Configuration section in the README for more information.
     * date mode will generate a name based on the date format specified in upload_date_format
     * random mode will generate a random name with length set in random_name_length
     */
    'sharex_upload_naming_scheme' => 'random',

    /* This sets the naming scheme to use for gallery uploads.
     * Possible values are: 
     *     keep, date, and random
     * keep mode will keep the name of the uploaded file
     * date mode will generate a name based on the date format specified in upload_date_format
     * random mode will generate a random name with length set in random_name_length
     */
    'gallery_upload_naming_scheme' => 'random',

    /* This sets the naming scheme to use if no file name is provided for a text upload.
     * Possible values are: 
     *     date and random
     * date mode will generate a name based on the date format specified in upload_date_format
     * random mode will generate a random name with length set in random_name_length
     */
    'text_upload_default_naming_scheme' => 'random',

    /* Select length of random name if random is chosen as the default_naming_scheme */
    'random_name_length' => 6,

    /* Select the date format to use to generate file names if date is chosen as a naming scheme */
    /* See available options at https://www.php.net/manual/en/function.date.php */
    'upload_date_format' => 'Y-m-d_H.i.s',

    /* This controls whether to enable uploader's built in updater
     * If enabled, the uploader will prompt you to update when a new version is released 
     *      If you agree, it will then automatically download and install the latest version
     * If you disable this, you will need to manually update the uploader by copying the new files into your website
     */
    'enable_updater' => true,

    /* This controls whether the uploader should make backups of the old version of the code when performing an update
     * If this is enabled, the update will copy the old versions of all modified files to the rollback folder
     * If you an update ever breaks your uploader, you can just go to <base_url>/rollback/update.php to roll back the update */
    'enable_update_rollback' => true
];
