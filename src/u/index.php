<?php
$config = include 'config.php';
include 'functions.php';
session_start();

if ($config['enable_delete'] && isset($_GET['action']) && 'delete' === $_GET['action']) {
    if (file_exists($_GET['filename'])) {
        unlink($_GET['filename']);
        $_SESSION['type'] = 'success';
        $_SESSION['message'] = 'You have successfully deleted <strong>' . $_GET['filename'] . '</strong>';
        die(header('Location: index.php'));
    } else {
        $_SESSION['type'] = 'danger';
        $_SESSION['message'] = 'File Does Not Exist!';
        die(header('Location: index.php'));
    }
}
?>
<html>
    <head>
    <head>      
        <title><?php echo $config['page_title']; ?></title>
        
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
        <!-- Bootstrap / Fontawesome  -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
        
        
        <!-- DataTables -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.20/datatables.min.css"/>
        <!-- theme -->
        <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">

    </head>
    </head>
    <body>
        <div class="container">
            <br />
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
            if (auth_user(false)) {
                $files1 = preg_grep('/^([^.])/', scandir('.'));
                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                if (!empty($_SESSION)) {
                    echo displayAlert($_SESSION['message'], $_SESSION['type']);
                    session_destroy();
                }
                ?>
                <br>

                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>FileName</th>
                            <th>Size (Bytes)</th>
                            <th>Date</th>
                            <th>Type</th>
                            <?php if ($config['enable_delete']) { ?>
                                <th>Option</th>
                            <?php } ?> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($files1 as $key => $file) {
                            if (is_dir($file) || 'php' === pathinfo($file, PATHINFO_EXTENSION)) {
                                unset($files1[$key]);
                            } else {
                                ?>
                                <tr>
                                    <td>
                                        <a target="_blank" href="<?php echo $config['output_url'];
                    echo $file;
                                ?>"
                                           <?php if ($config['enable_tooltip'] && isImage(finfo_file($finfo, $file))) { ?> 
                                               data-toggle="tooltip" data-html="true" data-placement="right" title="<img src='<?php echo $config['output_url'];
                                               echo $file;
                                               ?>' width='150px' alt='<?php echo $file; ?>'>"
                                    <?php } ?>>
                                    <?php echo $file; ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?php echo bytes_to_string(filesize($file)); ?>
                                    </td>
                                    <td>
                                        <?php echo date('d M Y H:i', filemtime($file)); ?>
                                    </td>
                                    <td>
                                    <?php echo pathinfo($file, PATHINFO_EXTENSION); ?>
                                    </td>
            <?php if ($config['enable_delete']) { echo $config['enable_delete']?>   
                                        <td>
                                            <a href="index.php?action=delete&filename=<?php echo $file; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently delete this file (<?php echo $file; ?>) ?');">
                                                delete file
                                            </a>
                                        </td>
                                <?php } ?> 
                                </tr>
        <?php } ?>
    <?php } ?>
                    </tbody>
                </table>
                <br />
                <br />
                <p>
                    <a href="/u/generate_custom_uploader_file.php" target="_blank">
                        Click here
                    </a>
                    to download your custom uploader file for shareX
                    <i>If this gets leaked, change your secure_key and re download this file</i>
                </p>
    <?php if (isset($config['enable_zip_dump']) && $config['enable_zip_dump']) { ?>
                    <p>
                        <a href="/u/generate_zip_of_files.php" target="_blank">
                            Click here
                        </a>
                        to download your files as a zip archive</a>
                    <p>
                <?php } ?>
            <?php } else { ?>
                <h2>Your IP is blocked from access, whitelist this ip to gain access: "<?php echo get_ip(); ?>"</h2>
        <?php } ?>

        <p>
                        <a href="/u/setup" >
                            Click here
                        </a>
                        to change settings</a>
                    <p>
        </div>

        <?php if ($config['enable_tooltip']) { ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <?php } ?>
        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.20/datatables.min.js"></script>
        <script src="js/main.js" type="text/javascript"></script>
    </body>
</html>
