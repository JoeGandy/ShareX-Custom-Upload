<?php

function phpversionCheck() {

    if (version_compare(phpversion(), '7.0', '>')) {
        echo '<i class="fas fa-check-circle text-success"></i>PHP version 7.0 or above';
    } else {
        echo '<i class="fas fa-minus-circle text-danger"></i>PHP version ' . phpversion() . ' is not 7.0';
    }
}

if (isset($_POST['settings'])) {


    $post = json_decode($_POST['settings']);
    $settings = new \stdClass;
    $settingsjson = null;
    $file = '../setup/settings.json';

    foreach ($post as $key => $value) {

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
    header('Location: ../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css">
        <link rel="stylesheet" href="https://bootswatch.com/4/flatly/bootstrap.min.css">

        <!-- Include Choices CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"
              />
        <!-- Include Choices JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

        <link rel="stylesheet" href="style.css">

        <title>ShareX Custom Upload</title>
    </head>
    <body>
        <div class="container">

            <div id="app" class="row pt-5 mt-5 ">
                <div class="col-10 offset-1">
                    <h1 class="text-white">ShareX Custom Upload <a href="https://github.com/JoeGandy/ShareX-Custom-Upload" class="text-white ml-3"><i class="fa-sm fab fa-github"></i></a></h1>
                </div>

                <form class="col-10 offset-1" method="post">
                    <div class="card shadow-sm form-group" v-if="step === 1">
                        <div class="card-body ">
                            <h1>Welcome</h1>
                            <h3>Let's get you setup!</h3>
                            <br>
                            <p>Before we go, we need to check if your server supports everything.</p>
                            <p><strong>Please be aware:</strong> <br>This script installs the required files in the current folder</p>
                            <p class="text-danger">Files may be overwritten</p>

                            <div class="d-flex justify-content-center pt-5 mt-5">
                                <button class="btn btn-primary btn-lg px-5" @click.prevent="next()">LET'S START</button>
                            </div>
                            <a target="_blank" href="https://github.com/JoeGandy/ShareX-Custom-Upload" class="text-dark github-btn"><i class="fa-lg fab fa-github"></i></a>
                        </div>
                    </div>

                    <div class="card shadow-sm form-group" v-if="step === 2">
                        <div class="card-body">
                            <h1 class=" mb-5">Let's do some checkups ...</h1>

                            <div class="row">
                                <ul class="mb-5 col-md-5">
                                    <!-- TODO: What features is required -->
                                    <li class="checklist animated fast fadeInRight"><?php phpversionCheck() ?></li>
                                    <!-- <li class="checklist animated fast fadeInRight delay-1s"><i class="fas fa-minus-circle text-danger"></i>Feature Enabled</li>
                                    <li class="checklist animated fast fadeInRight delay-2s"><i class="fas fa-check-circle text-success"></i>Feature Enabled</li>
                                    <li class="checklist animated fast fadeInRight delay-3s"><i class="fas fa-check-circle text-success"></i>Feature Enabled</li>
                                    <li class="checklist animated fast fadeInRight delay-4s"><i class="fas fa-check-circle text-success"></i>Feature Enabled</li> -->
                                </ul>
                            </div>

                            <div class="d-flex justify-content-end step-buttons">
                                <button class="btn btn-secondary mx-1" @click.prevent="prev()">Previous</button>
                                <button class="btn btn-primary mx-1" @click.prevent="next()">Next</button>
                            </div>
                            <a target="_blank" href="https://github.com/JoeGandy/ShareX-Custom-Upload" class="text-dark github-btn"><i class="fa-lg fab fa-github"></i></a>
                        </div>
                    </div>

                    <div class="card shadow-sm form-group" v-if="step === 3">
                        <div class="card-body">
                            <h1>Site details</h1>

                            <div class="form-group">
                                <label for="page_title" class="col-form-label">Page title</label>                  
                                <input id="page_title" v-model="config.page_title" :placeholder="placeholder.page_title" name="page_title" type="text" aria-describedby="page_titleHelpBlock" class="form-control">
                                <span id="page_titleHelpBlock" class="form-text text-muted">Page title of the gallery page</span>                  
                            </div>

                            <?php require '_image.php' ?>

                            <div class="d-flex justify-content-end step-buttons">
                                <button class="btn btn-secondary mx-1" @click.prevent="prev()">Previous</button>
                                <button class="btn btn-primary mx-1" @click.prevent="next()">Next</button>
                            </div>
                            <a target="_blank" href="https://github.com/JoeGandy/ShareX-Custom-Upload" class="text-dark github-btn"><i class="fa-lg fab fa-github"></i></a>
                        </div>
                    </div>

                    <div class="card shadow-sm form-group" v-if="step === 4">
                        <div class="card-body">
                            <h1>Security</h1>

                            <div class="form-group">
                                <label for="secure_key" class="col-form-label">Secure Key</label> 

                                <div class="input-group">

                                    <input id="secure_key" v-model="config.secure_key" :placeholder="placeholder.secure_key" name="secure_key" type="text" aria-describedby="secure_keyHelpBlock" class="form-control">

                                </div>                

                                <span id="secure_keyHelpBlock" class="form-text text-muted"><a @click.prevent="makekey()" id="newkey" href="">Generate random key</a></span>

                            </div>

                            <p>This is a secure key that only you should know, an added layer of security for the image upload</p>


                            <div class="d-flex justify-content-end step-buttons">
                                <button class="btn btn-secondary mx-1" @click.prevent="prev()">Previous</button>
                                <button class="btn btn-primary mx-1" @click.prevent="next()">Next</button>
                            </div>
                            <a target="_blank" href="https://github.com/JoeGandy/ShareX-Custom-Upload" class="text-dark github-btn"><i class="fa-lg fab fa-github"></i></a>
                        </div>
                    </div>

                    <div class="card shadow-sm form-group" v-if="step === 5">
                        <div class="card-body">
                            <h1>Site URL's</h1>

                            <div class="form-group">
                                <label for="output_url" class="form-label">Gallery url</label> 

                                <input id="output_url" name="output_url" v-model="config.output_url" :placeholder="placeholder.output_url" type="text" aria-describedby="output_urlHelpBlock" class="form-control" > 
                                <span id="output_urlHelpBlock" class="form-text text-muted">This is the url your output will be, usually http://www.domain.com/, also going to this url will be the gallery page</span>
                            </div>

                            <div class="form-group">
                                <label for="request_url" class="form-label">Request url</label> 

                                <input id="request_url" name="request_url" v-model="config.request_url" :placeholder="placeholder.request_url" type="text" aria-describedby="request_urlHelpBlock" class="form-control" v> 
                                <span id="request_urlHelpBlock" class="form-text text-muted">This request url, so the path pointing to the uplaod.php file</span>

                            </div>

                            <div class="form-group">
                                <label for="redirect_url" class="form-label">Redirect url</label> 

                                <input id="redirect_url" name="redirect_url" v-model="config.redirect_url" :placeholder="placeholder.redirect_url" type="text" aria-describedby="redirect_urlHelpBlock" class="form-control" > 
                                <span id="redirect_urlHelpBlock" class="form-text text-muted">This is a redirect url if the script is accessed directly</span>

                            </div>

                            <div class="d-flex justify-content-end step-buttons">
                                <button class="btn btn-secondary mx-1" @click.prevent="prev()">Previous</button>
                                <button class="btn btn-primary mx-1" @click.prevent="next()">Next</button>
                            </div>
                            <a target="_blank" href="https://github.com/JoeGandy/ShareX-Custom-Upload" class="text-dark github-btn"><i class="fa-lg fab fa-github"></i></a>
                        </div>
                    </div>

                    <div class="card shadow-sm form-group" v-if="step === 6">
                        <div class="card-body">
                            <h1>Other settings</h1>

                            <div class="form-group">
                                <label for="output_url" class="form-label">Gallery url</label> 
                                <input id="output_url" name="output_url" v-model="config.output_url" :placeholder="placeholder.output_url" type="text" aria-describedby="output_urlHelpBlock" class="form-control" > 
                                <span id="output_urlHelpBlock" class="form-text text-muted">This is the url your output will be, usually http://www.domain.com/, also going to this url will be the gallery page</span>
                            </div>

                            <div class="form-group">
                                <label for="enable_random_name" class="form-label">Generate random name</label><br>                                 
                                <label class="switch">                                        
                                    <input type="checkbox" v-model="config.enable_random_name"  name="enable_random_name" class="custom-select" aria-describedby="enable_random_nameHelpBlock" value="false" >
                                    <span class="slider round"></span>
                                </label>
                                <span id="enable_random_nameHelpBlock" class="form-text text-muted">Generate random name</span>                                
                            </div>

                            <div class="form-group">
                                <label for="random_name_length" class="form-label">Random name length</label>
                                <input id="random_name_length" v-model="config.random_name_length" name="random_name_length" type="text" class="form-control" aria-describedby="random_name_lengthHelpBlock"> 
                                <span id="random_name_lengthHelpBlock" class="form-text text-muted">Select lenght of random name</span>

                            </div> 

                            <div class="form-group">
                                <label for="enable_delete" class="form-label">Enable delete button</label><br>
                                <label class="switch">                                        
                                    <input type="checkbox" v-model="config.enable_delete" name="enable_delete" class="custom-select" aria-describedby="enable_deleteHelpBlock" value="tfalserue">
                                    <span class="slider round"></span>
                                </label>
                                <span id="enable_deleteHelpBlock" class="form-text text-muted">Delete file option</span>                                
                            </div>

                            <div class="form-group">
                                <label for="enable_tooltip" class="form-label">Enable tooltip hover</label> <br>
                                <label class="switch">                                        
                                    <input type="checkbox" v-model="config.enable_tooltip"  name="enable_tooltip" class="custom-select" aria-describedby="enable_tooltipHelpBlock" value="false" >
                                    <span class="slider round"></span>
                                </label>
                                <span id="enable_tooltipHelpBlock" class="form-text text-muted">Show image in tooltip</span>                                
                            </div>

                            <div class="form-group">
                                <label for="enable_lightbox" class="form-label">Enable lightbox</label> <br>
                                <label class="switch">                                        
                                    <input type="checkbox" v-model="config.enable_lightbox" name="enable_lightbox" class="custom-select" aria-describedby="enable_lightboxHelpBlock" value="false" >
                                    <span class="slider round"></span>
                                </label>
                                <span id="enable_lightboxHelpBlock" class="form-text text-muted">Show image in a lightbox gallery when clicking the link</span>

                            </div>

                            <div class="form-group">
                                <label for="enable_zip_dump" class="form-label">Enable Zip dump download</label><br>
                                <label class="switch">

                                    <input type="checkbox" v-model="config.enable_zip_dump" name="enable_zip_dump" class="custom-select" aria-describedby="enable_zip_dumpHelpBlock" value="false" >
                                    <span class="slider round"></span>
                                </label> 
                                <span id="enable_zip_dumpHelpBlock" class="form-text text-muted">show link to download all files as .zip</span>
                            </div>
                        </div>


                        <div class="d-flex justify-content-end step-buttons">
                            <button class="btn btn-secondary mx-1" @click.prevent="prev()">Previous</button>
                            <button class="btn btn-primary mx-1" @click.prevent="next()">Next</button>
                        </div>
                        <a target="_blank" href="https://github.com/JoeGandy/ShareX-Custom-Upload" class="text-dark github-btn"><i class="fa-lg fab fa-github"></i></a>
                    </div>

                    <div class="card shadow-sm form-group" v-if="step === 7">
                        <div class="card-body">
                            <h1>Login details</h1>

                            <div class="form-group ">
                                <label for="username" class="form-label">Username</label> 

                                <input id="username" v-model="config.username" name="username" placeholder="Username" type="text" aria-describedby="usernameHelpBlock" class="form-control"> 
                                <span id="usernameHelpBlock" class="form-text text-muted">username for the login site</span>

                            </div>

                            <div class="form-group ">
                                <label for="password" class="form-label">Password</label> 

                                <input id="password" v-model="config.password" name="password" placeholder="Password" type="text" aria-describedby="passwordHelpBlock" class="form-control"> 
                                <span id="passwordHelpBlock" class="form-text text-muted">update password for the login site</span>

                            </div>                          

                            <div class="d-flex justify-content-end step-buttons">
                                <button class="btn btn-secondary mx-1" @click.prevent="prev()">Previous</button>
                                <button class="btn btn-primary mx-1" @click="submit()">Save</button>
                            </div>
                            <a target="_blank" href="https://github.com/JoeGandy/ShareX-Custom-Upload" class="text-dark github-btn"><i class="fa-lg fab fa-github"></i></a>
                        </div>
                    </div>

                    <div class="form-group">
                        <textarea class="form-control d-none" name="settings" id="settings_debug" cols="30" rows="10">{{config}}</textarea>
                    </div>


                </form>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="script.js"></script>
        <script>
const element = document.querySelector('#allowed_ips');
const choices = new Choices(element, {
    silent: false,
    delimiter: ',',
    editItems: true,
    removeItemButton: true,
    classNames: {
        item: 'badge',
        itemSelectable: 'badge-tag',
        highlightedState: 'badge-tag',
        selectedState: 'badge-tag'
    }
}
);
        </script>
    </body>
</html>


