<?php


/**
 * 
 * 
 * 
 * 
 * 
 */
    function get_youtube_title($ref) {
      $json = file_get_contents('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' . $ref . '&format=json'); //get JSON video details
      $details = json_decode($json, true); //parse the JSON into an array
      return $details['title']; //return the video title
    }

/**
 * 
 * 

$customKeyboard = [
    ['7', '8', '9'],
    ['4', '5', '6'],
    ['1', '2', '3'],
         ['0']
];
$reply_markup = $tg->replyKeyboardMarkup($customKeyboard, true, true);


 * 
 * 
*/
function YouTubeSearch($para,$chatID){
   global $tg;
   if (strlen($para)<1){
    $msg = "Please use /youtube <Your Search String>" .  "\n" ."For example /youtube Muppets";
    TelegramTextMsg($chatID,$msg);
    return true;
   }
   $searchresults = SearchYoutube($para);
   $out ='';
   $lc=0;
   for($x=0;$x<count($searchresults);$x++){
    $out .= $searchresults[$x]['id'] . " -- " . $searchresults[$x]['title'] . "\n";
    
    
    $keys[$lc][] = array('text'=> "\xF0\x9F\x8E\xA5" . $searchresults[$x]['title']  ,'callback_data' => "/ytvideoCMD:" . $searchresults[$x]['id'] );
    $lc++;
    $keys[$lc][] = array('text'=> "\xF0\x9F\x8E\xB5:" . $searchresults[$x]['title']  ,'callback_data' => "/ytaudioCMD:" . $searchresults[$x]['id'] );
    $lc++;
    //$keys[] = $key[$x];
   }
   if(strlen($out)>0){
    //$customKeyboard[] = $keys;
    $reply_markup = InlineKeyboardMarkup($keys);
    $tg->sendMessage($chatID,"Search results",null,false,null,$reply_markup);
    
    
    
    
   }else{
    //no results
   }
}


/**
 * SearchYoutube
 * Search for given string in the YT videos and return max. 50 results as
 * array[] = array ('id' => 'xxxx', 'title' => 'yyyyy')
*/
function SearchYoutube($querystring,$searchnum=10){
    if($searchnum>50)$searchnum=50;
$results = array();    
$yt = new chopin2256\Youtube();  //Instantiate Youtube Object
$yt->key(YTAPIKEY);  //Set Youtube API Key Here
//Set Youtube Search Parameters
$yt->set()->
        q($querystring)->
        maxResults($searchnum)->
        order('relevance')->
        safeSearch('none')->
        videoDuration('any')->
        videoEmbeddable('true');

//Now get the Title and VideoIDS
$ytthumb = $yt->get()->thumbnail();
$num = count($yt->get()->id());  //Obtain number of results (taken from maxResults)

$link ="";
$ytIDArr = $yt->get()->id();  //Video ID array
$ytTitleArr = $yt->get()->title();  //Title array
for ($i = 0; $i < $num; $i++) {
    $ytID = $ytIDArr[$i];
    $ytTitle = $ytTitleArr[$i];
    $ytThumb = $ytthumb[$i]['url'];
    $results[$i]['id'] = $ytIDArr[$i];
    $results[$i]['title']  = $ytTitleArr[$i];
    //Your code here, for example, you can link to the youtube video results like so.  Ex:
    $link .= "<a href='http://www.youtube.com/watch?v=$ytID'>$ytTitle</a><br>";
}
$yt->clear();  //Clear query string, extremely important if iterating through multiple keywords!
return $results;
}


/**
 * GetYTVideo
 * Download the YT video and returns the local path
 * 
 * 
*/
function GetYTVideo($video_id,$forceOneFile = false){
   $yt = new YouTubeDownloader();
   $videolist = $yt->getDownloadLinks("https://www.youtube.com/watch?v=" .$video_id,"mp4");
   DebugOut($videolist,"VL");
   $video['file'] = $videolist['0']['url'] ;
   $video['title'] = get_youtube_title($video_id);
   $video['filename'] =  preg_replace( '/[^a-z0-9]+/', '-', strtolower( $video['title'] ) ) . ".mp4"; 
   if (substr($video['filename'],0,1)== "-") $video['filename'] = substr($video['filename'],1);
   $vidfile = VIDEODIR . $video['filename'];
   $viddir = VIDEODIR . "splitted/";
   if(!file_exists($vidfile))  file_put_contents($vidfile , fopen($video['file'], 'r'));
   if ($forceOneFile)return $vidfile;
   $sizeOS = 52428800;
   //$sizeOS = 1024000;
   $resize = 50000;
   //$resize = 1000;
   if (filesize($vidfile)< $sizeOS){
        return $vidfile;
   }
   //ok splitting it up
   $mp4b = "mp4box";
   $splitted = `$mp4b -splits $resize "$vidfile" -out "$viddir"`;
   unlink($vidfile);
//   error_log("$mp4b -splits $resize ".chr(32). $vidfile . chr(32) ." -out".chr(32). $viddir . chr(32));
    $ans = substr($video['filename'],0,-4);
    $len = strlen($ans);
    $vidfiles = array();
    if ($handle = opendir($viddir)) {
        while (false !== ($entry = readdir($handle))) {
            if ( substr($entry,0,$len) == $ans ) {
                $vidfiles[] = $viddir .$entry;
            }
        }
    closedir($handle);
    return $vidfiles;
    }
   //$video['filename'] 



   

}
//

/**
 * GetYTAudio
 * Download the YT video and extract the audio as mp3 and returns the local path
 * Video file will be deleted until $keepvid is set to true
 * 
*/
function GetYTAudio($video_id,$keepvid = false){
    $vidfile = GetYTVideo($video_id,true);
    if(!file_exists($vidfile)) return false;
    $audiofile = GetExtractedAudio($vidfile);
    if(!$keepvid) unlink($vidfile);
    if ($audiofile) return $audiofile;
    return false;
}

