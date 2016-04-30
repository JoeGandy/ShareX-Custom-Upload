<?php
$config = include('config.php');

$key = $config['secure_key'];
$uploadhost = $config['output_url'];
$redirect = $config['redirect_url'];

if ($_SERVER["REQUEST_URI"] == "/robot.txt") { die("User-agent: *\nDisallow: /"); }
 
if (isset($_POST['key'])) {
    if ($_POST['key'] == $key) {
        $target = getcwd() . "/u/" . $_POST['name'] . "." . end(explode(".", $_FILES["d"]["name"]));
        if (move_uploaded_file($_FILES['d']['tmp_name'], $target)) {
            echo $uploadhost . end(explode("/u/", $target));
        } else {
            echo "Sorry, there was a problem uploading your file.";
        }
    } else {
        header('Location: '.$redirect);
    }
} else {
    header('Location: '.$redirect);
}
?>