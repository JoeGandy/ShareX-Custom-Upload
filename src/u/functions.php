<?php

function displayAlert($text, $type) {
return "<div class=\"alert text-center alert-".$type."\" role=\"alert\">
        <p>".$text."</p>
      </div>";
}

function isImage($file)
{
  if( false === exif_imagetype($file) )
   return FALSE;

   return TRUE;
}