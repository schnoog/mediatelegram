<?php
error_reporting(E_ALL);
set_time_limit(30);
$basedir = __DIR__ ."/";
DEFINE('BASEDIR',$basedir);
DEFINE('CLASSDIR',$basedir . "classes/");
DEFINE('INCLUDEDIR',$basedir . "include/");
DEFINE('VIDEODIR',$basedir . "videos/");

require_once(INCLUDEDIR . "loader.php");

DebugOut("START","START");
$tg = new telegramBot(TELEGRAM_BOT_TOKEN);
GetTelegrams();

/**
 * 
 * 
 * 
 * 
 */


function GetTelegrams(){
global $tg;
$f = fopen(BASEDIR . '#atok', 'r');
$offset = fgets($f);
fclose($f);

//$offset = 0;
    echo "<br>StartOffset : " . $offset  ."<br>"; 
  $response = $tg->pollUpdates($offset, 10,20);
  $response = json_encode($response);
  $response = json_decode($response, true);
  $updated = false;  
  if ($response['ok'])
  {
    foreach($response['result'] as $data)
    {
        $updated = true;    
        $chatID = $data['message']['chat']['id'];
    DebugOut($data,"Data");
        list($command,$para) = explode(" ", $data['message']['text'],2);
        
        switch ($command)
        {
          case '/youtube':
            DebugOut ('Youtube:' . $para,"Youtube");
            YouTubeSearch($para,$chatID);
            break; 
          case '/start':
            DebugOut ('addContact($chatID)',"CID");
            break; 
          case '/remove':
                    case '/start':
            DebugOut ('deleteContact($chatID)',"CID");
            deleteContact($chatID);
            break;
        }
    }
    if($updated)$offset = $response['result'][count($response['result']) - 1]['update_id'] + 1;
    if($updated)file_put_contents(BASEDIR . "#atok",$offset);
  }

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
