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

/* This is a redirect url if the script is accessed directly */
  'redirect_url' => 'http://example.com/',

/* This is a list of IPs that can access the gallery page (Leave empty for universal access) */
  'allowed_ips' => array('192.168.0.0', '0.0.0.0'),

/* Page title of the gallery page */
  'page_title' => 'My Upload Site',

/* Heading text at the top of the gallery page */
  'heading_text' => 'Uploading Site',
  
/* Delete file option (true to enable, disabled by default) */
    'enable_delete' => false,
```

# ShareX Configuration
Next we need to setup our ShareX to use the custom uploader
```
1. From the ShareX main application we go to Destinations > Destination Settings
2. Scroll down to 'custom uploaders' add a new profile
3. Request type POST, the url should be http://www.example.com/upload.php
4. File form name: "d" (without quotation marks)
5. Arguments are:
    - key, this should be set to the 'secure key' you set in your config.php
    - name, this is how the files will be named, for mine, I use '%h.%mi.%s-%d.%mo.%yy'
6. The setup is now complete, test your uploader and it 'should' work!
```

# Preview of the gallery page
![Preview of gallery](http://jiy.io/22.40.35-07.03.17.png)

# Planned Features
1. Create an option to backup your files as a zip archive (optional through config)
2. Add a way to display images on hover, so you can quickly scan through (optional through config)
3. Password login for the page as an option in the config
