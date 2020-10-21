<?php

$config = include 'merge_config.php';
include 'functions.php';

session_start();
auth_user();

$upload_url = join_paths($config['base_url'], 'upload_text.php');
$key = join_paths($config['secure_key']);

if (!isset($_GET['type'])) {
    die(header('Location: '.$config['base_url']));
}

if ($_GET['type'] === 'bash') {
    $bash = <<<EOD
#!/bin/bash
if [ -p /dev/stdin ]; then
    if (( $# == 0 )); then
        curl -s -F "key=$key" -F "textcontent=<-" $upload_url
        echo
    elif (( $# == 1 )); then
        curl -s -F "key=$key" -F "filename=$1" -F "textcontent=<-" $upload_url
        echo
    fi
else
    echo 'Usage: somecommand | upload [filename]'
fi
EOD;
    header('Content-Disposition: attachment; filename=upload');
    header('Content-Type: text/plain');
    echo $bash;
} else if ($_GET['type'] === 'cmd') {
    $cmd = <<<EOD
@echo off

if "%1"=="" (
    curl -s -F "key=$key" -F "textcontent=<-" $upload_url
) else (
    curl -s -F "key=$key" -F "filename=%1" -F "textcontent=<-" $upload_url
)
EOD;
    header('Content-Disposition: attachment; filename=upload.bat');
    header('Content-Type: text/plain');
    echo $cmd;
} else if ($_GET['type'] === 'powershell') {
    $powershell = <<<EOD
function Upload {
    param (
        [Parameter(ValueFromPipeline = \$true)]
        [PSObject[]] \$InputObject,
        
        [Parameter(Position=0)]
        [String] \$Filename
    )

    BEGIN {
        if (!\$PSBoundParameters.ContainsKey('InputObject') -and !\$PSCmdlet.MyInvocation.ExpectingInput) {
            throw 'Usage: somecommand | upload [filename]'
        }
        \$Collector = [System.Collections.ArrayList]@()
    }
    PROCESS {
        [void]\$Collector.Add(\$_)
    }
    END {
        \$StrOutput = Out-String -InputObject \$Collector

        if (\$PSBoundParameters.ContainsKey('Filename')) {
            curl -s -F "key=$key" -F "filename=\$Filename" -F "textcontent=\$StrOutput" $upload_url
        } else {
            curl -s -F "key=$key" -F "textcontent=\$StrOutput" $upload_url
        }
    }
}
EOD;
    header('Content-Disposition: attachment; filename=upload.ps1');
    header('Content-Type: text/plain');
    echo $powershell;
}
