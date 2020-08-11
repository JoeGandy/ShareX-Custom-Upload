# NGINX Configuration Guide
Since NGINX does not support directory-level configuration like Apache, you will need to modify your server configuration to make it work with the uploader.

If you are using Linux, NGINX's default server configuration is likely to be located at `/etc/nginx/sites-available/default`.

Below is an example of what your configuration file will need to look like to work with the uploader:

```nginx
server {
        listen 80 default_server;
        listen [::]:80 default_server;

        root /path/to/website/root/; # change this

        index index.html index.php index.htm index.nginx-debian.html;

        server_name _;

        location / { # change this
            try_files $uri $uri/ /rewrite.php?$args; # change this
        }

        location /u { deny all; } # change this
        location = /config.php { deny all; } # change this
        location = /login.json { deny all; } # change this

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        }

        location ~ /\.ht {
               deny all;
        }
}
```

To make the configuration work, you will need to modify all the lines marked by comments.

However, this is just an example of what the configuration can look like and yours does not need to match. The only lines that are *absolutely necessary* for the uploader are the following: 

```nginx
location / { # change this
    try_files $uri $uri/ /rewrite.php?$args; # change this
}

location /u { deny all; } # change this
location = /config.php { deny all; } # change this
location = /login.json { deny all; } # change this
```

If you already have an NGINX server configured to work with PHP, you can just copy those lines into your configuration.

If you plan to install your uploader at the root of your website, most of the configuration will work without being changed.

If you plan to change your `file_storage_folder` to something other than `u`, you will need to modify the `location /u { deny all; }` to include your `file_storage_folder` instead of `/u`.

If your uploader will not be installed in your website's root directory, you will need to modify the `location` lines to include the path to your uploader.

For example, if I want to install my uploader at `https://www.example.com/myuploader`, I will need to put the files into `/path/to/website/root/myuploader/`. Then, I will need to modify the NGINX configuration to this:

```nginx
location /myuploader { # change this
    try_files $uri $uri/ /myuploader/rewrite.php?$args; # change this
}

location /myuploader/u { deny all; } # change this
location = /myuploader/config.php { deny all; } # change this
location = /myuploader/login.json { deny all; } # change this
```

After changing the configuration, you can restart NGINX and the uploader should work.

If you have any problems, please [open an issue on GitHub](https://github.com/JoeGandy/ShareX-Custom-Upload/issues).
