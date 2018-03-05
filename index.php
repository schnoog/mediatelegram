<?php
error_reporting(E_ALL);
set_time_limit(300);
$basedir = __DIR__ ."/";
DEFINE('BASEDIR',$basedir);
DEFINE('CLASSDIR',$basedir . "classes/");
DEFINE('INCLUDEDIR',$basedir . "include/");
DEFINE('VIDEODIR',$basedir . "videos/");

require_once(INCLUDEDIR . "loader.php");

DebugOut("START","START");
$tg = new telegramBot(TELEGRAM_BOT_TOKEN);


//YouTubeSearch("shortest video on youtube",549279974);
GetTelegrams();

//YouTubeSearch("dead parrot",549279974);

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
        $subpart = 'message';
            if (isset($data[$subpart])){     
                    $chatID = $data[$subpart]['chat']['id'];
                    DebugOut($data,"Data");
                    list($command,$para) = explode(" ", $data['message']['text'],2);
                    DebugOut(array($command,$para),"Command");
                    switch ($command)
                    {
                      case '/youtube':
                        DebugOut ('Youtube:' . $para,"Youtube");
                        YouTubeSearch($para,$chatID);
                        break; 
                      case '/start':
                        $helptext = "Welcome to the MediaPoster Bot\n";
                        $helptext .= "Ever wanted an easy way to get Youtube videos or audio files?\n";
                        $helptext .= "You have made contact to the right bot.\n";
                        $helptext .= "MediaPoster-Help\n\n/youtube <Searchterm> - Search for Youtube video\nFor each result two button will be available.\n";
                        $helptext .= "One starting with V: for Video, one with A: for Audio\nClick the button and the selected video or audio file will be sent to you";
                        $helptext .= "\n/ytvideo <VideoID> - Get the mp4 video file sent via telegram\n";
                        $helptext .= "\n/ytaudio <VideoID> - Get the mp3 audio file sent via telegram\n";
                        TelegramTextMsg($chatID,$helptext);
                        break; 
                      case '/help':
                        $helptext = "MediaPoster-Help\n\n/youtube <Searchterm> - Search for Youtube video\nFor each result two button will be available.\n";
                        $helptext .= "One starting with V: for Video, one with A: for Audio\nClick the button and the selected video or audio file will be sent to you";
                        $helptext .= "\n/ytvideo <VideoID> - Get the mp4 video file sent via telegram\n";
                        $helptext .= "\n/ytaudio <VideoID> - Get the mp3 audio file sent via telegram\n";

                        TelegramTextMsg($chatID,$helptext);
                        break;
                      case '/ytvideo':
                            $exfile = GetYTVideo($para);
                            if(file_exists($exfile)) SendSpecDocToChat($chatID,$exfile,true);                      
                        break;
                      case '/ytaudio':
                            $exfile = GetYTAudio($para); 
                            if(file_exists($exfile)) SendSpecDocToChat($chatID,$exfile,true);                      
                        break;

                    }
            }
        $subpart = 'callback_query';        
            if (isset($data[$subpart])){
                    DebugOut($data,"CallbackQuery");   
                    $chatID = $data[$subpart]['message']['chat']['id'];
                    list($callbackCMD,$para) = explode("CMD:",$data[$subpart]['data']);
                    echo "<h1>Command: $callbackCMD  from Chat $chatID </h1>";
                    echo "<h1>Parameter : ". $para ."</h1>";
                    
                    switch($callbackCMD){
                        case '/ytvideo':
                            $exfile = GetYTVideo($para);
                            if(file_exists($exfile)) SendSpecDocToChat($chatID,$exfile,true);
                            break;
                            
                        case '/ytaudio':
                            $exfile = GetYTAudio($para); 
                            if(file_exists($exfile)) SendSpecDocToChat($chatID,$exfile,true);
                            break;
                        
                        
                        
                    }
                    
                    
                    
                    

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
