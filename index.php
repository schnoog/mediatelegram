<?php
error_reporting(E_ALL);
set_time_limit(120);
$basedir = __DIR__ ."/";
DEFINE('BASEDIR',$basedir);
DEFINE('CLASSDIR',$basedir . "classes/");
DEFINE('INCLUDEDIR',$basedir . "include/");
DEFINE('VIDEODIR',$basedir . "videos/");

require_once(INCLUDEDIR . "loader.php");

$video_id = 'VnT7pT6zCcA';

$vfile = GetYTAudio($video_id);
if (@file_exists($vfile)){
    echo "File: " . $vfile;
}else{ 
    @DebugOut($vfile,"Fehler");
}


//$results = SearchYoutube("Dotterbart");
//DebugOut($results,"RESULTS");

function GetYTAudio($video_id){
    $vidfile = DownloadYTVideo($video_id);
    $audiofile = ExtractAudio($vidfile);
    if ($audiofile) return $audiofile;
    return false;
}

function ExtractAudio($videofile){   
        $audiofile = $videofile . ".mp3";
    	$FFmpeg = new FFmpeg(FFMPEG);
    	$FFmpeg->input( $videofile )->output( $audiofile )->ready();
        if (file_exists($audiofile)) return $audiofile;
        return false;
}

