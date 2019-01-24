<?php

function displayAlert($text, $type) {
return "<div class=\"alert text-center alert-".$type."\" role=\"alert\">
        <p>".$text."</p>
      </div>";
}

function isImage($file)
{
	$image_formats = ['image/png','image/jpeg','image/gif','image/svg+xml'];
  if (!in_array($file,$image_formats) )
   return FALSE;

   return TRUE;
}

function generateRandomName($type,$length) {

	$name = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	 if ( ! file_exists(__DIR__ . "/" . $name . "." . $type)) {
        return $name. "." .$type;
    } else {
        return generateRandomName($type,$length);
    }
}
