<?php


//
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $ffmpegbin = "ffmpeg.exe";
} else {
    $ffmpegbin = "ffmpeg.exe";
}
DEFINE('FFMPEG',$ffmpegbin);
require_once(INCLUDEDIR . "config.php");
require_once(CLASSDIR . "YoutubeDL.php");
require_once(INCLUDEDIR . "func.php");
require_once(CLASSDIR . "Youtube_SEARCH/Youtube.php");
require_once(BASEDIR . "vendor/autoload.php"); 