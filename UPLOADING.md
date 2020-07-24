# Uploading Documentation
Want to make something that works with your uploader? You're in luck! We've got some documentation.

> Throughout this page, `<base_url>` will be used to refer to the `base_url` setting in your configuration, which is the URL where you can access your gallery page.

The uploader offers two API endpoints for uploading content. There is currently no API for viewing/managing files.

## Image/File Uploading
To upload a file, you'll need to send a POST request to `<base_url>/upload.php`. The data sent must be encoded as `multipart/form-data`, which is the standard HTML form encoding for file uploads.

The file name will follow the settings in your `config.php` for ShareX uploads. If you have `provided` set as your `sharex_upload_naming_scheme`, you can use the `name` parameter to specify the file name to use.

### ***POST*** `<base_url>/upload.php`

| Field        | Type        | Optional | Description                                |
| ------------ | ----------- | -------- | ------------------------------------------ |
| `key`        | text        | no       | The `secure_key` set in your `config.php`. |
| `name`       | text        | yes      | The file name to use if you have `provided` set as the `sharex_upload_naming_scheme` |
| `fileupload` | file        | no       | The file to upload                         |


## Text Uploading
To upload some text, you'll need to send a POST request to `<base_url>/upload_text.php`. The data can either be encoded as `multipart/form-data` or `application/x-www-form-urlencoded`.

You can either specify a file name with the `filename` parameter or leave it blank, in which case the `default_naming_scheme` set in your `config.php` will be used. If the `filename` parameter does not include a file extension, the `txt` extension will automatically be used.

### ***POST*** `<base_url>/upload_text.php`

| Field         | Type        | Optional | Description                               |
| ------------- | ----------- | -------- | ----------------------------------------- |
| `key`         | text        | no       | The `secure_key` set in your `config.php` |
| `textcontent` | text        | no       | The text content of the file              |
| `filename`    | text        | yes      | The name of the file. If this is not set, the `default_naming_scheme` from your `config.php` will be used.                        |
