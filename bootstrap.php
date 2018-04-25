<?php


define("DIR_BASE",__DIR__ . "/");
define("DIR_FUNC", DIR_BASE . "FunctionsAndClasses/");
define("VIDEODIR", DIR_BASE . "videos/");
define("SPLITDIR", VIDEODIR . "splitted/");


error_reporting(E_ALL);

$nl = "<br />";
$cli=false;
if(php_sapi_name() === 'cli' OR defined('STDIN')){
    $nl = "\n";
    $cli=true;
}

require DIR_BASE . '/vendor/autoload.php';
foreach (glob(DIR_FUNC . "*.php") as $filename)
{
    include_once $filename;
}


require DIR_BASE . '/include/config.php';
require_once DIR_FUNC   . "Youtube_SEARCH/Youtube.php";

if (isset($Config['DB']['telegram'])){
    DB::$user       = $Config['DB']['telegram']['user'];
    DB::$password   = $Config['DB']['telegram']['password'];
    DB::$dbName     = $Config['DB']['telegram']['database'];
    DB::$host       = $Config['DB']['telegram']['host']; //defaults to localhost if omitted
  //  DB::$port       = $Config['DB']['telegram']['port']; // defaults to 3306 if omitted
    DB::$encoding   = 'utf8'; // defaults to latin1 if omitted
}

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $mp4box    = "mp4box.exe";
    $ffmpegbin = "ffmpeg.exe";
} else {
    $mp4box    = "mp4box";
    $ffmpegbin = "ffmpeg";
}
DEFINE('FFMPEG',$ffmpegbin);
DEFINE('MP4BOX',$mp4box);