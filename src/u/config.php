<?php

$settings = file_get_contents(__DIR__ . "/setup/settings.json");
$settings = json_decode($settings, JSON_UNESCAPED_SLASHES);


return $settings;
