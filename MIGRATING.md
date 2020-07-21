# Migrating From Version 1
Version 2 of this project introduced major breaking changes which make it incompatible with earlier versions.

This guide has instructions for how to preserve your uploaded files when upgrading from version 1.

> If you don't need to keep your uploaded images, just clear the directory where you installed the uploader and follow the normal installation instructions in [README.md](README.md)

> *Important: In Version 2, the gallery page is accessed directly from your website root, not from the `u/` directory.*
> 
> This means that if you previously accessed the gallery page from `https://www.example.com/u/`, it will now be at `https://www.example.com` in Version 2.

To back up your uploads, open your website root directory in a terminal and create a new directory called `backupfiles` using this command:
```
mkdir backupfiles
```

Next, navigate to the `u/` folder by running
```
cd u
```

Once in the directory, run the following command to copy all your uploaded files into the `backupfiles` folder you just created:
```
find . -maxdepth 1 -not -name "*.php" -exec cp '{}' ../backupfiles/ ';'
```

Then, make a copy of your configuration file with the following command:
```
cp config.php ../config-old.php
```

Now, you can delete the old uploader code by running the following commands:
```
cd ..
rm -rf ./u/
rm upload.php
```

You should now be able to install a new version of the uploader by following the instructions in [README.md](README.md).

Now, if you installed the uploader to the root of your website, run the following command to add your images to the new version of the uploader:
```
mv backupfiles u
```

## Migrating Configuration

The configuration file of Version 2 is incompatible with that of Version 1, but many settings are still the same. These are: `secure_key`, `allowed_ips`, `page_title`, `heading_text`, `enable_delete`, `enable_tooltip`, `enable_zip_dump`, and `random_name_length`.

You can just copy your old settings for these values from `config-old.php` directly into the new configuration file.

However, you will need to configure the other settings manually. Read the config documentation in [README.md](README.md#full-configuration) for more information.

## ShareX Uploading

The Version 1 ShareX configuration is incompatible with the new version, so you will need to generate and add a new ShareX configuration file with Version 2.
