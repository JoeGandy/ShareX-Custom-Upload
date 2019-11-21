<?php
if (isset($_POST['save'])) {

    $settings = new \stdClass;
    $settingsjson = null;
    $file = 'settings.json';

    foreach ($_POST as $key => $value) {

        if ($key == "allowed_ips") {
            $value = str_replace(' ', '', $value);
            $value = explode(",", $value);
        }

        if($value == "true"){
            $value = true;
        }elseif ($value == "false") {
            $value = false;
        }

        $settings->$key = $value;

        $settingsjson = json_encode($settings, JSON_UNESCAPED_SLASHES);
    }  

    // Write the contents to the file.
    file_put_contents($file, $settingsjson);
    die(header('Location: index.php'));
}

$config = include '../config.php';
include '../functions.php';
session_start();
?>

<html>
    <head>      
        <title><?php echo $config['page_title']; ?></title>
        
        <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
        <!-- Bootstrap / Fontawesome  -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <!-- DataTables -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.20/datatables.min.css"/>
        
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
                <p>
                    <a href="/u" class=" btn btn-primary">
                        Back</a>
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
                        <label for="heading_text" class="col-3 col-form-label">Heading text</label> 
                        <div class="col-9">
                            <input id="heading_text" name="heading_text" type="text" aria-describedby="heading_textHelpBlock" class="form-control" value="<?php setFieldContent("heading_text") ?>"> 
                            <span id="heading_textHelpBlock" class="form-text text-muted">Heading text at the top of the gallery page</span>
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
                            </div>                

                            <span id="secure_keyHelpBlock" class="form-text text-muted">This is a secure key that only you should know, an added layer of security for the image upload<br><a id="newkey" href="">Generate new key</a></span>

                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="output_url" class="col-3 col-form-label">Output url</label> 
                        <div class="col-9">
                            <input id="output_url" name="output_url" placeholder="https://sharex/u/" type="text" aria-describedby="output_urlHelpBlock" class="form-control" value="<?php setFieldContent("output_url") ?>"> 
                            <span id="output_urlHelpBlock" class="form-text text-muted">This is the url your output will be, usually http://www.domain.com/u/, also going to this url will be the gallery page</span>
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

                    <div class="form-group row">
                        <label for="allowed_ips" class="col-3 col-form-label">Allowed IPs</label> 
                        <div class="col-9">
                            <input id="allowed_ips" name="allowed_ips" type="text" aria-describedby="allowed_ipsHelpBlock" class="form-control" value="<?php setFieldContent("allowed_ips") ?>"> 
                            <span id="allowed_ipsHelpBlock" class="form-text text-muted">This is a list of IPs that can access the gallery page (Leave empty for universal access) <br>Seperate with ","</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="enable_random_name" class="col-3 col-form-label">Generate random name</label> 
                        <div class="col-9">
                            <select id="enable_random_name" name="enable_random_name" class="custom-select" aria-describedby="enable_random_nameHelpBlock">
                                <?php setFieldTrueFalse("enable_random_name") ?>
                            </select> 
                            <span id="enable_random_nameHelpBlock" class="form-text text-muted">Generate random name</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="enable_delete" class="col-3 col-form-label">Enable delete button</label> 
                        <div class="col-9">
                            <select id="enable_delete" name="enable_delete" class="custom-select" aria-describedby="enable_deleteHelpBlock">
                                <?php setFieldTrueFalse("enable_delete") ?>
                            </select> 
                            <span id="enable_deleteHelpBlock" class="form-text text-muted">Delete file option</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="enable_tooltip" class="col-3 col-form-label">Enable tooltip hover</label> 
                        <div class="col-9">
                            <select id="enable_tooltip" name="enable_tooltip" class="custom-select" aria-describedby="enable_tooltipHelpBlock">
                                <?php setFieldTrueFalse("enable_tooltip") ?>
                            </select> 
                            <span id="enable_tooltipHelpBlock" class="form-text text-muted">Show image in tooltip</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="enable_zip_dump" class="col-3 col-form-label">Enable Zip dump download</label> 
                        <div class="col-9">
                            <select id="enable_zip_dump" name="enable_zip_dump" class="custom-select" aria-describedby="enable_zip_dumpHelpBlock">
                                <?php setFieldTrueFalse("enable_zip_dump") ?>
                            </select> 
                            <span id="enable_zip_dumpHelpBlock" class="form-text text-muted">how link to download all files as .zip</span>
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="copykey">Copy that!</button>
                            </div>
                        </div> 
                    </div>
                </div>



            </div>


        <?php } else { // if auth_user   ?> 
            <h2>Your IP is blocked from access, whitelist this ip to gain access: "<?php echo get_ip(); ?>"</h2>
        <?php } //if auth_user   ?>


    </div>

    <?php if ($config['enable_tooltip']) { ?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <?php } ?>
        
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/dt-1.10.20/datatables.min.js"></script>
    <script src="../js/main.js" type="text/javascript"></script>

    <script>

        $("#show_hide").on('click', function (event) {

            if ($('#secure_key').attr("type") == "text") {
                $('#secure_key').attr('type', 'password');
                $('#show_hide_password i').addClass("fa-eye-slash");
                $('#show_hide_password i').removeClass("fa-eye");
            } else if ($('#secure_key').attr("type") == "password") {
                $('#secure_key').attr('type', 'text');
                $('#show_hide_password i').removeClass("fa-eye-slash");
                $('#show_hide_password i').addClass("fa-eye");
            }
        });

        $("#newkey").on('click', function (event) {
            event.preventDefault();
            const key = makeid(33);
            $('#secure_key').attr('value', key);
            $('#secure_keyModal').attr('value', key);

            $('#newkeygenerated').modal('show');

        });

        $("#copykey").on('click', function (event) {
            event.preventDefault();
            CopyKey();
            $('#secure_key').attr('type', 'text');
            $('#show_hide_password i').removeClass("fa-eye-slash");
            $('#show_hide_password i').addClass("fa-eye");
            $('#newkeygenerated').modal('hide');

        });

    </script>

</body>
</html>
