<?php
// test

$config = include 'config.php';
include 'functions.php';
session_start();
auth_user();

create_webmanifest();
?>
<html>
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <link rel="stylesheet" href="css/toggle-bootstrap.min.css">
    <link rel="stylesheet" href="css/toggle-bootstrap-dark.min.css">
    <link
        href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"
        rel="stylesheet"
        type="text/css">
    <link rel="stylesheet" href="css/main.css">
    <title><?php echo $config['page_title']; ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="icons/favicon-16x16.png">
    <link rel="manifest" href="manifest.webmanifest">
    <link rel="mask-icon" href="icons/safari-pinned-tab.svg" color="#5c5cbc">
    <link rel="shortcut icon" href="favicon.ico">
    <meta name="msapplication-TileColor" content="#5c5cbc">
    <meta name="msapplication-config" content="icons/browserconfig.xml">
    <meta name="theme-color" content="#5c5cbc">

</head>
<body class="bootstrap" data-dateformat="<?php echo $config['gallery_date_format']; ?>">
    <div class="container">
        <br/>
        <h3 class="text-center"><?php echo $config['heading_text']; ?></h3>
        <p class="text-center">
            <?php
            echo 'Free space: ' .
                get_total_free_space_string() .
                ' / ' .
                get_total_space_string();
            ?>
        </p>

        <?php
            $dir_path = join_paths(getcwd(), $config['file_storage_folder']);
            if (!file_exists($dir_path)) {
                mkdir($dir_path, 0777, true);
            }

            $files = preg_grep('/^([^.])/', scandir($dir_path));
            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            if (!empty($_SESSION) && isset($_SESSION['message']) && isset($_SESSION['type'])) {
                echo display_alert($_SESSION['message'], $_SESSION['type']);
                unset($_SESSION['message']);
                unset($_SESSION['type']);
            }
        ?>
        <br/>
        <?php
            $current_version_path = join_paths(getcwd(), 'VERSION');
            $new_version_path = join_paths(getcwd(), 'release', 'VERSION');
            if (file_exists(join_paths(getcwd(), 'release', 'update.php')) &&
                file_exists($current_version_path) &&
                file_exists($new_version_path)) {
                $current_version = file_get_contents($current_version_path);
                $new_version = file_get_contents($new_version_path);

                if (version_compare($current_version, $new_version) === -1) {
                    ?>
                        <div class="alert text-center alert-warning" role="alert">
                            <h4 class="alert-heading">Update Available!</h4>
                            <p>An update has been detected. Click the button below to update your uploader.</p>
                            <a href="release/update.php" type="button" class="btn btn-warning">Update</a>
                        </div><br>
                    <?php
                }
            }
        ?>

        <?php if ($config['enable_gallery_page_uploads']) { ?>
            <div class="btn-group w-100" id="upload-mode" role="group" aria-label="Select File or Text Upload">
                <button type="button" class="btn btn-outline-secondary active" id="file-upload-mode">File Upload</button>
                <button type="button" class="btn btn-outline-secondary" id="text-upload-mode">Text Upload</button>
            </div>
            <form action="upload.php" method="POST"
                class="dropzone jumbotron d-flex w-100 flex-column justify-content-center align-items-center border border-secondary"
                id="gallery-uploader">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-earmark-arrow-up-fill dz-message" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M2 3a2 2 0 0 1 2-2h5.293a1 1 0 0 1 .707.293L13.707 5a1 1 0 0 1 .293.707V13a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3zm7 2V2l4 4h-3a1 1 0 0 1-1-1zM6.354 9.854a.5.5 0 0 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 8.707V12.5a.5.5 0 0 1-1 0V8.707L6.354 9.854z"/>
                </svg>
                <br/>
                <h3 class="text-center">Drop Files Here or Click To Upload</h3>
            </form>
            <form action="upload_text.php" method="POST"
                class="is-hidden jumbotron d-none w-100 flex-column justify-content-center align-items-end border border-secondary"
                id="text-uploader">
                <h3 class="text-center w-100 mb-3">Upload Text</h3>
                <div class="form-group w-100">
                    <label for="filename">File Name (or leave blank to automatically generate a name)</label>
                    <input type="text" class="form-control" id="filename" name="filename" placeholder="filename.txt">
                </div>
                <div class="form-group w-100">
                    <label for="textcontent">File Content</label>
                    <textarea class="form-control" id="textcontent" name="textcontent" rows="6" spellcheck="false" wrap="off" placeholder="Lorem ipsum dolor sit amet..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Upload</button>
            </form>
        <?php } ?>

        <div class="collapse btn-group w-100 mb-4" role="group" aria-label="Bulk Operations" id="bulk-buttons">
            <button type="button" class="btn btn-primary" id="bulk-download">Download Selected Files As ZIP</button>
            <button type="button" class="btn btn-danger" id="bulk-delete">Delete Selected Files</button>
        </div>

        <table id="file-table" class="table table-striped table-bordered  dt-responsive" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>File Name</th>
                <th>Size (Bytes)</th>
                <th>Date</th>
                <th>Type</th>
                <?php if ($config['enable_rename'] || $config['enable_delete']) { ?>
                    <th></th>
                <?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($files as $key => $file) {
                $file_path = join_paths(getcwd(), $config['file_storage_folder'], $file);

                if (is_dir($file)) {
                    unset($files[$key]);
                } else { ?>
                    <tr data-filename="<?php echo $file; ?>" >
                        <td>
                            <?php if ($config['enable_bulk_operations']) { ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input position-static bulk-select" type="checkbox"
                                        aria-label="Select this item for bulk operations.">
                                </div>
                            <?php } ?>
                            <a target="_blank" href="<?php echo join_paths($config['base_url'], $config['upload_access_path'], $file); ?>"
                                <?php if ($config['enable_tooltip'] && is_image(finfo_file($finfo, $file_path))) { ?>
                                    data-toggle="tooltip" data-html="true" data-placement="right" title="<img src='<?php echo join_paths($config['base_url'], $config['upload_access_path'], $file);
                                ?>' width='150px' alt='<?php echo $file; ?>'>"
                                <?php } ?>>
                                <?php echo $file; ?>
                            </a>
                        </td>
                        <td>
                            <?php echo bytes_to_string(filesize($file_path)); ?>
                        </td>
                        <td>
                            <?php echo filemtime($file_path) ?>
                        </td>
                        <td>
                            <?php echo pathinfo($file_path, PATHINFO_EXTENSION); ?>
                        </td>
                        <?php if ($config['enable_rename'] || $config['enable_delete']) { ?>
                            <td class="text-center">
                            <?php if ($config['enable_rename']) { ?>
                                <button class="btn btn-primary my-1 rename-button"
                                    tabindex="0" role="button">
                                    Rename
                                </button>
                            <?php } ?>
                            <?php if ($config['enable_delete']) { ?>
                                <a href="delete_files.php?files[]=<?php echo urlencode($file); ?>" class="btn btn-danger my-1"
                                    onclick="return confirm('Are you sure you want to permanently delete this file (<?php echo $file; ?>)?');">
                                    Delete
                                </a>
                            <?php } ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
        <br/>
        <div class="IconButtons d-flex flex-row justify-content-center">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-sun" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="top" title="Switch to Light Theme" aria-labelledby="light-title" role="button" tabindex="0">
                <title id="light-title">Switch to Light Theme.</title>
                <path d="M3.5 8a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0z"/>
                <path fill-rule="evenodd" d="M8.202.28a.25.25 0 0 0-.404 0l-.91 1.255a.25.25 0 0 1-.334.067L5.232.79a.25.25 0 0 0-.374.155l-.36 1.508a.25.25 0 0 1-.282.19l-1.532-.245a.25.25 0 0 0-.286.286l.244 1.532a.25.25 0 0 1-.189.282l-1.509.36a.25.25 0 0 0-.154.374l.812 1.322a.25.25 0 0 1-.067.333l-1.256.91a.25.25 0 0 0 0 .405l1.256.91a.25.25 0 0 1 .067.334L.79 10.768a.25.25 0 0 0 .154.374l1.51.36a.25.25 0 0 1 .188.282l-.244 1.532a.25.25 0 0 0 .286.286l1.532-.244a.25.25 0 0 1 .282.189l.36 1.508a.25.25 0 0 0 .374.155l1.322-.812a.25.25 0 0 1 .333.067l.91 1.256a.25.25 0 0 0 .405 0l.91-1.256a.25.25 0 0 1 .334-.067l1.322.812a.25.25 0 0 0 .374-.155l.36-1.508a.25.25 0 0 1 .282-.19l1.532.245a.25.25 0 0 0 .286-.286l-.244-1.532a.25.25 0 0 1 .189-.282l1.508-.36a.25.25 0 0 0 .155-.374l-.812-1.322a.25.25 0 0 1 .067-.333l1.256-.91a.25.25 0 0 0 0-.405l-1.256-.91a.25.25 0 0 1-.067-.334l.812-1.322a.25.25 0 0 0-.155-.374l-1.508-.36a.25.25 0 0 1-.19-.282l.245-1.532a.25.25 0 0 0-.286-.286l-1.532.244a.25.25 0 0 1-.282-.189l-.36-1.508a.25.25 0 0 0-.374-.155l-1.322.812a.25.25 0 0 1-.333-.067L8.203.28zM8 2.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11z"/>
            </svg>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-moon" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="top" title=" Switch to Dark Theme" aria-labelledby="dark-title" role="button" tabindex="0">
                <title id="light-title">Switch to Dark Theme.</title>
                <path fill-rule="evenodd" d="M14.53 10.53a7 7 0 0 1-9.058-9.058A7.003 7.003 0 0 0 8 15a7.002 7.002 0 0 0 6.53-4.47z"/>
            </svg>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-clockwise" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="top" title="Reset to System Default Theme" aria-labelledby="reset-title" role="button" tabindex="0">
                <title id="reset-title">Reset to System Default Theme.</title>
                <path fill-rule="evenodd" d="M3.17 6.706a5 5 0 0 1 7.103-3.16.5.5 0 1 0 .454-.892A6 6 0 1 0 13.455 5.5a.5.5 0 0 0-.91.417 5 5 0 1 1-9.375.789z"/>
                <path fill-rule="evenodd" d="M8.147.146a.5.5 0 0 1 .707 0l2.5 2.5a.5.5 0 0 1 0 .708l-2.5 2.5a.5.5 0 1 1-.707-.708L10.293 3 8.147.854a.5.5 0 0 1 0-.708z"/>
            </svg>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-share-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="top" title="Generate ShareX Custom Uploader Config File" aria-labelledby="sharex-title" role="button" tabindex="0">
                <title id="sharex-title">Generate ShareX Custom Uploader Config File.</title>
                <path fill-rule="evenodd" d="M12.024 3.797L4.499 7.56l-.448-.895 7.525-3.762.448.894zm-.448 9.3L4.051 9.335 4.5 8.44l7.525 3.763-.448.894z"/>
                <path fill-rule="evenodd" d="M13.5 5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm0 11a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5zm-11-5.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
            </svg>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-terminal-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="top" title="Generate Shell Uploader Script" aria-labelledby="shell-title" role="button" tabindex="0">
                <title id="shell-title">Generate Shell Uploader Script.</title>
                <path fill-rule="evenodd" d="M0 3a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3zm9.5 5.5h-3a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zm-6.354-.354L4.793 6.5 3.146 4.854a.5.5 0 1 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708z"/>
            </svg>
            <?php if (isset($config['enable_zip_dump']) && $config['enable_zip_dump']) { ?>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-zip-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="top" title="Download a ZIP Archive of Your Files" aria-labelledby="zip-title" role="button" tabindex="0">
                <title id="zip-title">Download a ZIP Archive of Your Files.</title>
                <path fill-rule="evenodd" d="M8 1h4a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h2.5v1h1v1h-1v1h1v1h-1v1h1v1H9V6H8V5h1V4H8V3h1V2H8V1zM6.5 8.5a1 1 0 0 1 1-1h1a1 1 0 0 1 1 1v.938l.4 1.599a1 1 0 0 1-.416 1.074l-.93.62a1 1 0 0 1-1.109 0l-.93-.62a1 1 0 0 1-.415-1.074l.4-1.599V8.5zm2 .938V8.5h-1v.938a1 1 0 0 1-.03.243l-.4 1.598.93.62.93-.62-.4-1.598a1 1 0 0 1-.03-.243z"/>
            </svg>
            <?php } ?>
            <?php if (isset($config['enable_delete_all']) && $config['enable_delete_all']) { ?>
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                    data-toggle="tooltip" data-placement="top" title="Delete All Uploads" aria-labelledby="deleteall-title" role="button" tabindex="0">
                    <title id="deleteall-title">Delete All Uploads.</title>
                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"/>
                </svg>
            <?php } ?>
            <?php if ($config['enable_password_login']) { ?>
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-box-arrow-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                data-toggle="tooltip" data-placement="top" title="Log Out" aria-labelledby="logout-title" role="button" tabindex="0">
                <title id="logout-title">Log Out.</title>
                <path fill-rule="evenodd" d="M11.646 11.354a.5.5 0 0 1 0-.708L14.293 8l-2.647-2.646a.5.5 0 0 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0z"/>
                <path fill-rule="evenodd" d="M4.5 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                <path fill-rule="evenodd" d="M2 13.5A1.5 1.5 0 0 1 .5 12V4A1.5 1.5 0 0 1 2 2.5h7A1.5 1.5 0 0 1 10.5 4v1.5a.5.5 0 0 1-1 0V4a.5.5 0 0 0-.5-.5H2a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5v-1.5a.5.5 0 0 1 1 0V12A1.5 1.5 0 0 1 9 13.5H2z"/>
            </svg>
            <?php } ?>
        </div>
        <p class="text-center">
            <i>If your ShareX config file is leaked, change the secret_key and redownload the file.</i>
        </p>
    </div>
    <div class="modal fade" id="uploader-script-modal" tabindex="-1" role="dialog" aria-labelledby="uploader-script-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploader-script-modal-title">Select Your Preferred Shell Environment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <a href="generate_shell_uploader.php?type=bash" class="btn btn-primary btn-block my-1"
                        onclick="$('#uploader-script-modal').modal('hide'); return true;">
                        Bash
                    </a>
                    <br>
                    <a href="generate_shell_uploader.php?type=cmd" class="btn btn-primary btn-block my-1"
                        onclick="$('#uploader-script-modal').modal('hide'); return true;">
                        Windows Command Prompt
                    </a>
                    <br>
                    <a href="generate_shell_uploader.php?type=powershell" class="btn btn-primary btn-block my-1"
                        onclick="$('#uploader-script-modal').modal('hide'); return true;">
                        PowerShell
                    </a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<script
    src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<?php if ($config['enable_gallery_page_uploads']) { ?>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.1/min/dropzone.min.js"
    integrity="sha256-v7sFPKIh3GHvV9MMFBXcSBLG/BDUC7h1fpfDC5tp1FM="
    crossorigin="anonymous"></script>
<?php } ?>
<script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"></script>
<script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
    integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
    crossorigin="anonymous"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"
    type="text/javascript"
    crossorign="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.21/sorting/file-size.js" type="text/javascript"></script>
<script src="js/dataTableDateTimeRender.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/responsive.bootstrap4.min.js" type="text/javascript"></script>
<script src="js/common.js" type="text/javascript"></script>
<script src="js/main.js" type="text/javascript"></script>
</body>
</html>
