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

//DebugOut($dbd,"DB");
//YouTubeSearch("shortest video on youtube",549279974);



GetTelegrams();

/*
$vid = 'vOTViwuzNZk';
$vid = 'tPEE9ZwTmy0';  //shortest....
echo "<hr>" . time() . "<br>";
SingleCall('549279974','ytvideo',$vid);
*/
echo  time() . "<br>";

//$vf = GetYTVideo($vid);
//DebugOut($title,"TITLE");
//$vid  = 'B7bqAsxee4I';

//$yt = new YouTubeDownloader();
//$videolist = $yt->getDownloadLinks("https://www.youtube.com/watch?v=" .$vid,"mp4");
//$video['file'] = $videolist['0'];
//$video['title'] = get_youtube_title($vid);
//$video['filename'] =  preg_replace( '/[^a-z0-9]+/', '-', strtolower( $video['title'] ) ) . ".mp4";
//DebugOut($vf,"VIDEO");
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
                        $helptext .= "MediaPoster-Help\n\n/youtube <Searchterm or Video-ID> - Search for Youtube video\nFor each result two button will be available.\n";
                        $helptext .= "One starting with \xF0\x9F\x8E\xA5 for Video, one with \xF0\x9F\x8E\xB5 for Audio\nClick the button and the selected video or audio file will be sent to you";
                        $helptext .= "\n/ytvideo <VideoID> - Get the mp4 video file sent via telegram\n";
                        $helptext .= "\n/ytaudio <VideoID> - Get the mp3 audio file sent via telegram\n";
                        $helptext .= "\n\nBig video files will be splitted into smaller parts\n";
                        TelegramTextMsg($chatID,$helptext);
                        break; 
                      case '/help':
                        $helptext = "MediaPoster-Help\n\n/youtube <Searchterm or Video-ID> - Search for Youtube video\nFor each result two button will be available.\n";
                        $helptext .= "One starting with \xF0\x9F\x8E\xA5 for Video, one with \xF0\x9F\x8E\xB5 for Audio\nClick the button and the selected video or audio file will be sent to you";
                        $helptext .= "\n/ytvideo <VideoID> - Get the mp4 video file sent via telegram\n";
                        $helptext .= "\n/ytaudio <VideoID> - Get the mp3 audio file sent via telegram\n";
                        $helptext .= "\n\nBig video files will be splitted into smaller parts\n";
                        
                        TelegramTextMsg($chatID,$helptext);
                        break;
                      case '/ytvideo':
                            TelegramWaitMsg($chatID);
                            SingleCall($chatID,'ytvideo',$para);                      
                        break;
                      case '/ytaudio':
                            TelegramWaitMsg($chatID);
                            SingleCall($chatID,'ytaudio',$para);                      
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
                            TelegramWaitMsg($chatID);                        
                            SingleCall($chatID,'ytvideo',$para);                      
                            break;
                            
                        case '/ytaudio':
                            TelegramWaitMsg($chatID);                        
                            SingleCall($chatID,'ytaudio',$para);                      
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
