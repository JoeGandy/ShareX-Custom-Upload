<?php

$settings = file_get_contents(__DIR__ . "/setup/settings.json");
$settings = json_decode($settings, JSON_UNESCAPED_SLASHES);

$version = ['version' => '1.0.0'];
$settings = $version + $settings;

return $settings;
?>
