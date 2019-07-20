<?php

function displayAlert($text, $type) {
return '<div class="alert text-center alert-'.$type.'" role="alert">
        <p>'.$text.'</p>
      </div>';
}

function isImage($file)
{
    $image_formats = ['image/png', 'image/jpeg', 'image/gif', 'image/svg+xml'];
  if (!in_array($file, $image_formats))
   return false;

   return true;
}

function generateRandomName($type, $length) {
    $name = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
     if (!file_exists(__DIR__.'/'.$name.'.'.$type)) {
        return $name.'.'.$type;
    } else {
        return generateRandomName($type, $length);
    }
}

function get_latest_sharex_version() {
  $opts = [
      'http' => [
              'method' => 'GET',
              'header' => [
                      'User-Agent: PHP',
              ],
      ],
  ];

  $context = stream_context_create($opts);
  $content = json_decode(file_get_contents('https://api.github.com/repos/ShareX/ShareX/releases/latest', false, $context));
  return str_replace('v', '', $content->tag_name);
}

function get_total_free_space_string() {
  $si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  $base = 1024;

  $bytes = disk_free_space('/');
  $class = min((int) log($bytes, $base), count($si_prefix) - 1);

  return sprintf('%1.2f', $bytes / pow($base, $class)).' '.$si_prefix[$class];
}

function get_total_space_string(){
  $si_prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
  $base = 1024;

  $bytes = disk_total_space('/');
  $class = min((int) log($bytes, $base), count($si_prefix) - 1);

  return sprintf('%1.2f', $bytes / pow($base, $class)).' '.$si_prefix[$class];

}

function auth_or_kill(){
  $config = include 'config.php';
  
  if(
      !empty($config['allowed_ips']) && 
      !in_array($_SERVER['REMOTE_ADDR'], $config['allowed_ips'])
    ){
    die('You are not authed to continue this action, this ip needs to be whitelisted in the config: \'' . $_SERVER['REMOTE_ADDR'] . '\'');
  }
}