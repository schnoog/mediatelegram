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
//error_log("Singlecall " . time());
$tg = new telegramBot(TELEGRAM_BOT_TOKEN);


if (isCommandLineInterface()){
//$shortopts="cmd:para:chatID";
//$longopts="cmd:para:chatID";
//$opts=getopt($shortopts,$longopts);
DebugOut($argv,"OPT");    
$command    = $argv[1];    
$para       = $argv[2];
$chatID     = $argv[3];    
}else{
$command=$_REQUEST['cmd'];
$para=$_REQUEST['para'];
$chatID=$_REQUEST['chatID'];
}
//error_log("Command $command Parameter $para  chatID $chatID");
switch($command){

                      case 'ytvideo':
                            
                            $exfile = GetYTVideo($para);
                            
                            
                            TelegramSendFiles($chatID,$exfile,true);                     
                        break;
                      case 'ytaudio':
                                                  
                            $exfile = GetYTAudio($para); 
                            TelegramSendFiles($chatID,$exfile,true);                    
                        break;
 
} 
