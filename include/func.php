<?php
/**
 * 
 * 
*/

/**
 * 
 * 
*/
function SearchYoutube($querystring){
$results = array();    
$yt = new chopin2256\Youtube();  //Instantiate Youtube Object
$yt->key(YTAPIKEY);  //Set Youtube API Key Here

//Set Youtube Search Parameters
$yt->set()->
        q($querystring)->
        maxResults(50)->
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
 * 
 * 
*/

function DownloadYTVideo($video_id){
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
/**
 * 
 * 
*/

function DebugOut($data,$label=""){
    echo "<hr>";
    if ($label != "") echo "<h2>$label</h2>";
    echo "<pre>" . print_r($data,true) . "</pre>";
    echo "<hr>";
    
}

/**
 * 
 * 
*/


/**
 * 
 * 
*/


/**
 * 
 * 
*/
