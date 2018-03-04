<?php
error_reporting(E_ALL);
set_time_limit(120);
$basedir = __DIR__ ."/";
DEFINE('BASEDIR',$basedir);
DEFINE('CLASSDIR',$basedir . "classes/");
DEFINE('INCLUDEDIR',$basedir . "include/");
DEFINE('VIDEODIR',$basedir . "videos/");

require_once(INCLUDEDIR . "loader.php");

GetTelegrams();

function GetTelegrams(){
    
    $telegram = new Telegram\Bot\Api(TELEGRAM_BOT_TOKEN);
    $response = $telegram->getUpdates(array('offset' => 303606306));
    $resp = json_decode(json_encode($response), true);
    DebugOut($resp,"RESP");
    
}







/**
 * 
//ode $video_id = 'VnT7pT6zCcA';
$video_id = 'B7bqAsxee4I';
 
$vfile = GetYTAudio($video_id,true);
if (@file_exists($vfile)){
    echo "File: " . $vfile;
}else{ 
    @DebugOut($vfile,"Fehler");
}


//$results = SearchYoutube("Dotterbart");
//DebugOut($results,"RESULTS");


*/
