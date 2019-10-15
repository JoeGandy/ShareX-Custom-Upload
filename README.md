# ShareX-Custom-Upload
A little PHP script created for uploading custom sharex files to your own webserver

# Setup
First we start by uploading the contents of the 'src' directory to the root of our website

Next is the configuration file, found in /u/config.php here there are a few key settings
```
/* This is a secure key that only you should know, an added layer of security for the image upload */
  'secure_key' => 'somerandomlongstringoftextforkey',

/* This is the url your output will be, usually http://www.domain.com/u/, also going to this url will be the gallery page */
  'output_url' => 'http://example.com/u/',

/* This request url, so the path pointing to the uplaod.php file */
  'request_url' => 'http://example.com/upload.php',

/* This is a redirect url if the script is accessed directly */
  'redirect_url' => 'http://example.com/',

/* This is a list of IPs that can access the gallery page (Leave empty for universal access) */
  'allowed_ips' => ['::1', '127.0.0.1'],

/* Page title of the gallery page */
  'page_title' => 'My Upload Site',

/* Heading text at the top of the gallery page */
  'heading_text' => 'Uploading Site',
  
/* Delete file option (true to enable, disabled by default) */
  'enable_delete' => false,
    
/* Show image in tooltip  (true to enable, disabled by default) */
  'enable_tooltip' => false,

/* Show link to download all files as .zip (Untested with large archives of files) */
  'enable_zip_dump' => false,

/* Generate random name (true to enable, disabled by default) */
  'enable_random_name' => false,

/* Select length of random name (10 symbols by default) */
  'random_name_length' => 10,

/* if set to false the directory_for_uploads will define the folder for uploads, else the root directory will be used */
  'upload_to_root' => false,
    
/* defines an custom upload directory (default is u) */
   'directory_for_uploads' => 'u',
```

# ShareX Configuration

## Automatic setup
We've added a auto generater to get you up and running quickly in ShareX, simply go to the gallery site, in the config above thats: `http://example.com/u/`
Once you have access to the gallery, a link will appear the bottom that will generate an import file for shareX to automagically setup the enviroment for you.

## Manual setup
Alteratively if you have a trouble with the above, below is the old manual setup process
```
1. From the ShareX main application we go to Destinations > Custom uploader settings
2. Add a new profile (Uploaders onthe left)
3. Request type POST, the url should be http://www.example.com/upload.php
4. File form name: "d" (without quotation marks)
5. Arguments are:
    - "key"(without quotation marks), this should be set to the 'secure key' you set in your config.php
    - "name"(without quotation marks), this is how the files will be named, for mine, I use '%h.%mi.%s-%d.%mo.%yy'
6. The setup is now complete, test your uploader and it 'should' work!
```

# Preview of the gallery page
![Preview of gallery](http://u.jiy.io/u/20.54.45-19.07.19.png)

# Planned Features
[View the tasks board here](https://github.com/JoeGandy/ShareX-Custom-Upload/projects/1)
