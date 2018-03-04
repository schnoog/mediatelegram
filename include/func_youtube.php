<?php



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
   for($x=0;$x<count($searchresults);$x++){
    $out .= $searchresults[$x]['id'] . " -- " . $searchresults[$x]['title'] . "\n";
    $keys[] = array('xx'=>$searchresults[$x]['title'],'yy' => $searchresults[$x]['id'] );
   }
   if(strlen($out)>0){
    $customKeyboard[] = $keys;
    $reply_markup = InlineKeyboardMarkup($customKeyboard);
    $tg->sendMessage($chatID,"Search results",null,false,null,$reply_markup);
    
    
    
    
   }else{
    //no results
   }
}

/**
 * GetYTAudio
 * Download the YT video and extract the audio as mp3 and returns the local path
 * Video file will be deleted until $keepvid is set to true
 * 
*/
function GetYTAudio($video_id,$keepvid = false){
    $vidfile = GetYTVideo($video_id);
    $audiofile = GetExtractedAudio($vidfile);
    if(!$keepvid) unlink($vidfile);
    if ($audiofile) return $audiofile;
    return false;
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
$num = count($yt->get()->id());  //Obtain number of results (taken from maxResults)

$link ="";
$ytIDArr = $yt->get()->id();  //Video ID array
$ytTitleArr = $yt->get()->title();  //Title array
for ($i = 0; $i < $num; $i++) {
    $ytID = $ytIDArr[$i];
    $ytTitle = $ytTitleArr[$i];
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

function GetYTVideo($video_id){
    $y =  new Smoqadam\Youtube();
    $url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    $url .= $_SERVER["SERVER_NAME"];
    if($y->init($video_id) !== false){

        $dlfile = $y->download();
        $dlfile = str_replace($url,BASEDIR , $dlfile); 
    	$msg = "Download finished! \n".$y->download();
        DebugOut($msg,"MSG");
        return $dlfile;
    }else{
      	$msg = $y->getError();
        return false;
    }      
}
