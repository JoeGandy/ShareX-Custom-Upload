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