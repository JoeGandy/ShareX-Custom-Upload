<?php
session_start();

if ($_SESSION['AUTH_ID'] != 34234) {
    header('Location: login.php');
}
$config = include 'config.php';
include 'functions.php';

if ($config['enable_delete'] && isset($_GET['action']) && 'delete' === $_GET['action']) {
    if (file_exists($_GET['filename'])) {
        unlink($_GET['filename']);

        $alert['type'] = 'success';
        $alert['message'] = 'You have successfully deleted <strong>' . $_GET['filename'] . '</strong>';

        // die(header('Location: index.php'));
    } else {
        ?>



        <?php
        $alert['type'] = 'danger';
        $alert['message'] = 'File Does Not Exist!';
        // die(header('Location: index.php'));
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

        <!-- Include Choices JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
        <!-- theme -->
        <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">

        <link rel="stylesheet" href="css/lightbox.min.css">
        <link rel="stylesheet" href="css/style.css">

    </head>
</head>
<body>
    <?php
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./">Galleri </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./setup">Settings</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Tools
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <?php if (isset($config['enable_zip_dump']) && $config['enable_zip_dump']) { ?>
                                <a class="dropdown-item" href="./generate_zip_of_files.php">Create & download zip backup</a>
                                <div class="dropdown-divider"></div>
                            <?php } ?>
                            <a class="dropdown-item" href="./generate_custom_uploader_file.php" data-toggle="tooltip" data-html="true" data-placement="right" title="If this gets leaked, change your secure_key and re download this file">Download setup file for ShareX</a>
                        </div></a>                                

                    </li>
                    <li class="nav-item ml-auto">
                        <a class="nav-link" href="./login.php?logout" data-toggle="tooltip" data-html="true" data-placement="bottom" title="This will log you out">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">

        <br />
        <h3 class="text-center"><?php echo $config['page_title']; ?></h3>

        <p class="text-center">
            <?php
            echo 'Free space: ' .
            get_total_free_space_string() .
            ' / ' .
            get_total_space_string();
            ?>
        </p>
        <?php
        $files1 = preg_grep('/^([^.])/', scandir('.'));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (!empty($_SESSION)) {
            //echo displayAlert($_SESSION['message'], $_SESSION['type']);
            // session_destroy();
        }
        ?>
        <br>

        <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>FileName</th>
                    <th>Size</th>
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
                                <a href="#" class=" mr-2 fa-lg fas fa-copy" id="copyurl" data-copyurl="<?php
                                echo $config['output_url'];
                                echo $file;
                                ?>" data-toggle="tooltip" data-html="true" data-placement="bottom" title="Copy image url"></a>
                                <a target="_blank" 
                                <?php
                                if ($config['enable_lightbox']) {
                                    ?>
                                       data-lightbox="gallery" data-title="<?php
                                       echo $config['output_url'];
                                       echo $file;
                                       ?>" 
                                       <?php
                                   }
                                   ?>
                                   href="<?php
                                   echo $config['output_url'];
                                   echo $file;
                                   ?>"
                                   <?php if ($config['enable_tooltip'] && isImage(finfo_file($finfo, $file))) { ?> 
                                       data-toggle="tooltip" data-html="true" data-placement="right" title="<img src=' <?php
                                       echo $config['output_url'];
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
                            <?php if ($config['enable_delete']) { ?>   
                                <td>
                                    <a href="index.php?action=delete&filename= <?php echo $file; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently delete this file (<?php echo $file; ?>) ?');">
                                        delete file
                                    </a>
                                </td>
                            <?php } ?> 
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>        
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.20/datatables.min.js"></script>
    <script src="js/bootstrap-notify.min.js" type="text/javascript"></script>
    <script src="js/lightbox.min.js" type="text/javascript"></script>
    <script src="js/main.js" type="text/javascript"></script>


    <?php
    if (isset($alert['message'])) {
        ?>
        <script>
                                $.notify({
                                    // options
                                    message: '<?php echo $alert['message'] ?>'
                                }, {
                                    // settings
                                    type: '<?php echo $alert['type'] ?>'
                                });
        </script>
        <?php
    }
    ?>
    <?php require 'components/footer.php' ?>
</body>
</html>
