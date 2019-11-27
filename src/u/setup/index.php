<?php
session_start();

if ($_SESSION['AUTH_ID'] != 34234) {
    header('Location: ../login.php');
}


$config = include '../config.php';
include '../functions.php';

if (isset($_POST['save'])) {
    $settings = new \stdClass;
    $settingsjson = null;
    $file = 'settings.json';

    foreach ($_POST as $key => $value) {

        if ($key == "allowed_ips") {


            if ($value == "") {
                $value = "";
                echo "empty";
            } else {
                $value = str_replace(' ', '', $value);
                $value = explode(",", $value);
                echo "not empty";
            }
        }

        if ($key == "password") {
            if ($value == "") {
                $value = $config['password'];
            } else {
                $value = sha1($value);
            }
        }

        if ($value == "true") {
            $value = true;
        } elseif ($value == "false") {
            $value = false;
        }

        $settings->$key = $value;

        $settingsjson = json_encode($settings, JSON_UNESCAPED_SLASHES, JSON_PRETTY_PRINT);
    }

    // Write the contents to the file.
    file_put_contents($file, $settingsjson);
    header('Location: index.php');
}
?>

<html>
    <head>      
        <title><?php echo $config['page_title']; ?></title>

        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
        <!-- Bootstrap / Fontawesome  -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
        <!-- Include Choices CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
              />
        <!-- Include Choices JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

        <!-- DataTables -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.20/datatables.min.css"/>
        <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">
        <link rel="stylesheet" href="../css/style.css">


    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="..">Galleri </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../setup">Settings</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Tools
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php if (isset($config['enable_zip_dump']) && $config['enable_zip_dump']) { ?>
                                    <a class="dropdown-item" href="../generate_zip_of_files.php">Create & download zip backup</a>
                                    <div class="dropdown-divider"></div>
                                <?php } ?>
                                <a class="dropdown-item" href="../generate_custom_uploader_file.php" data-toggle="tooltip" data-html="true" data-placement="right" title="If this gets leaked, change your secure_key and re download this file">Download setup file for ShareX</a>
                            </div></a>                                

                        </li>
                        <li class="nav-item ml-auto">
                            <a class="nav-link" href="../login.php?logout" data-toggle="tooltip" data-html="true" data-placement="bottom" title="This will log you out">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container pb-5">

            <br>
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

            // if (!empty($_SESSION)) {
            //     echo displayAlert($_SESSION['message'], $_SESSION['type']);
            //     session_destroy();
            // }
            ?>
            <br>
            <p>
                <a href="./.." class="btn btn-primary">Go Back</a>
            <p>
            <form method="POST" action="index.php">
                <div class="form-group row">
                    <label for="page_title" class="col-3 col-form-label">Page title</label> 
                    <div class="col-9">
                        <input id="page_title" name="page_title" type="text" aria-describedby="page_titleHelpBlock" class="form-control" value="<?php setFieldContent("page_title") ?>"> 
                        <span id="page_titleHelpBlock" class="form-text text-muted">Page title of the gallery page</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="secure_key" class="col-3 col-form-label">Secure Key</label> 
                    <div class="col-9">
                        <div class="input-group">

                            <input id="secure_key" name="secure_key" placeholder="somerandomlongstringoftextforkey" type="password" aria-describedby="secure_keyHelpBlock" class="form-control" value="<?php setFieldContent("secure_key") ?>">
                            <div class="input-group-append" id="show_hide_password">
                                <div class="input-group-text" id="show_hide"><i class="far fa-fw fa-eye-slash" aria-hidden="true"></i></div>
                            </div>
                        </div><span id="secure_keyHelpBlock" class="form-text text-muted">This is a secure key that only you should know, an added layer of security for the image upload<br><a id="newkey" href="">Generate new key</a></span>

                    </div>
                </div>

                <div class="form-group row">
                    <label for="output_url" class="col-3 col-form-label">Output url</label> 
                    <div class="col-9">
                        <input id="output_url" name="output_url" placeholder="https://sharex/" type="text" aria-describedby="output_urlHelpBlock" class="form-control" value="<?php setFieldContent("output_url") ?>"> 
                        <span id="output_urlHelpBlock" class="form-text text-muted">This is the url your output will be, usually http://www.domain.com/, also going to this url will be the gallery page<br>Current URL: <strong><?php echo url() ?></strong></span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="request_url" class="col-3 col-form-label">Request url</label> 
                    <div class="col-9">
                        <input id="request_url" name="request_url" placeholder="https://sharex/upload.php" type="text" aria-describedby="request_urlHelpBlock" class="form-control" value="<?php setFieldContent("request_url") ?>"> 
                        <span id="request_urlHelpBlock" class="form-text text-muted">This request url, so the path pointing to the uplaod.php file</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="redirect_url" class="col-3 col-form-label">Redirect url</label> 
                    <div class="col-9">
                        <input id="redirect_url" name="redirect_url" placeholder="https://sharex/" type="text" aria-describedby="redirect_urlHelpBlock" class="form-control" value="<?php setFieldContent("redirect_url") ?>"> 
                        <span id="redirect_urlHelpBlock" class="form-text text-muted">This is a redirect url if the script is accessed directly</span>
                    </div>
                </div>

                <!-- <div class="form-group row">
                    <label for="allowed_ips" class="col-3 col-form-label">Allowed IPs</label> 
                    <div class="col-9">
                        <input class="form-control" id="allowed_ips" name="allowed_ips" type="text" value="<?php //setFieldContent("allowed_ips")         ?>" aria-describedby="allowed_ipsHelpBlock" placeholder="Enter allowed IPs"/>
                        <span id="allowed_ipsHelpBlock" class="form-text text-muted">This is a list of IPs that can access the gallery page (Leave empty for universal access) <br>Your current ip is: <strong><?php //echo get_ip();         ?></strong></span>
                    </div>
                </div> -->

                <div class="form-group row">
                    <label for="enable_random_name" class="col-3 col-form-label">Generate random name</label> 
                    <div class="col-9">
                        <label class="switch">
                            <input type="hidden" name="enable_random_name" class="custom-select" aria-describedby="enable_random_nameHelpBlock" value="false">
                            <input type="checkbox" name="enable_random_name" class="custom-select" aria-describedby="enable_random_nameHelpBlock" value="true" <?php setFieldTrueFalse("enable_random_name") ?>>
                            <span class="slider round"></span>
                        </label>
                        <span id="enable_random_nameHelpBlock" class="form-text text-muted">Generate random name</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="random_name_length" class="col-3 col-form-label">Random name length</label> 
                    <div class="col-9">
                        <input id="random_name_length" name="random_name_length" type="text" class="form-control" aria-describedby="random_name_lengthHelpBlock" value="<?php setFieldContent("random_name_length") ?>"> 
                        <span id="random_name_lengthHelpBlock" class="form-text text-muted">Select lenght of random name</span>
                    </div>
                </div> 

                <div class="form-group row">
                    <label for="enable_delete" class="col-3 col-form-label">Enable delete button</label> 
                    <div class="col-9">

                        <label class="switch">
                            <input type="hidden" name="enable_delete" class="custom-select" aria-describedby="enable_deleteHelpBlock" value="false">
                            <input type="checkbox" name="enable_delete" class="custom-select" aria-describedby="enable_deleteHelpBlock" value="true" <?php setFieldTrueFalse("enable_delete") ?>>
                            <span class="slider round"></span>
                        </label>
                        <span id="enable_deleteHelpBlock" class="form-text text-muted">Delete file option</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="enable_tooltip" class="col-3 col-form-label">Enable tooltip hover</label> 
                    <div class="col-9">
                        <label class="switch">
                            <input type="hidden" name="enable_tooltip" class="custom-select" aria-describedby="enable_tooltipHelpBlock" value="false">
                            <input type="checkbox" name="enable_tooltip" class="custom-select" aria-describedby="enable_tooltipHelpBlock" value="true" <?php setFieldTrueFalse("enable_tooltip") ?>>
                            <span class="slider round"></span>
                        </label>
                        <span id="enable_tooltipHelpBlock" class="form-text text-muted">Show image in tooltip</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="enable_lightbox" class="col-3 col-form-label">Enable lightbox</label> 
                    <div class="col-9">
                        <label class="switch">
                            <input type="hidden" name="enable_lightbox" class="custom-select" aria-describedby="enable_lightboxHelpBlock" value="false">
                            <input type="checkbox" name="enable_lightbox" class="custom-select" aria-describedby="enable_lightboxHelpBlock" value="true" <?php setFieldTrueFalse("enable_lightbox") ?>>
                            <span class="slider round"></span>
                        </label>
                        <span id="enable_lightboxHelpBlock" class="form-text text-muted">Show image in a lightbox gallery when clicking the link</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="enable_zip_dump" class="col-3 col-form-label">Enable Zip dump download</label> 
                    <div class="col-9">
                        <label class="switch">
                            <input type="hidden" name="enable_zip_dump" class="custom-select" aria-describedby="enable_zip_dumpHelpBlock" value="false">
                            <input type="checkbox" name="enable_zip_dump" class="custom-select" aria-describedby="enable_zip_dumpHelpBlock" value="true" <?php setFieldTrueFalse("enable_zip_dump") ?>>
                            <span class="slider round"></span>
                        </label> 
                        <span id="enable_zip_dumpHelpBlock" class="form-text text-muted">how link to download all files as .zip</span>
                    </div>
                </div>

                <h3>Login Details</h3>

                <div class="form-group row">
                    <label for="username" class="col-3 col-form-label">Username</label> 
                    <div class="col-9">
                        <input id="username" name="username" placeholder="Username" type="text" aria-describedby="usernameHelpBlock" class="form-control" value="<?php setFieldContent("username") ?>"> 
                        <span id="usernameHelpBlock" class="form-text text-muted">username for the login site</span>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="password" class="col-3 col-form-label">Update password</label> 
                    <div class="col-9">
                        <input id="password" name="password" placeholder="New password" type="text" aria-describedby="passwordHelpBlock" class="form-control"> 
                        <span id="passwordHelpBlock" class="form-text text-muted">update password for the login site</span>
                    </div>
                </div>



                <div class="form-group row">
                    <div class="offset-3 col-9">
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>




            <!-- Modal -->
            <div class="modal fade" id="newkeygenerated" tabindex="-1" role="dialog" aria-labelledby="newkeygeneratedLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="newkeygeneratedLabel">New secret key</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>New secret key has been generated.<br>
                                Remember to change it in your ShareX settings.</p>
                            <input id="secure_keyModal" name="secure_key" type="text" aria-describedby="secure_keyHelpBlock" class="form-control" value="">
                            <br>
                            <p>Click button to copy</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="copykey">Copy that!</button>
                        </div>
                    </div> 
                </div>
            </div>
        </div>


    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" ></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> 
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.20/datatables.min.js"></script>
    <script src="../js/bootstrap-notify.min.js" type="text/javascript"></script>
    <script src="../js/main.js" type="text/javascript"></script>

    <?php require '../components/footer.php' ?>
</body>
</html>
