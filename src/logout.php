<?php

$config = include 'merge_config.php';
include 'functions.php';

session_start();
auth_user();

log_out();
header('Location: '.join_paths($config['base_url'], 'login'));
die();
