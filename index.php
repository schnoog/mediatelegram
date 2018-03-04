<?php

$basedir = __DIR__ ."/";
DEFINE('BASEDIR',$basedir);
DEFINE('CLASSDIR',$basedir . "classes/");
DEFINE('INCLUDEDIR',$basedir . "include/");

require_once(INCLUDEDIR . "loader.php");

$video_id = 'B7bqAsxee4I';

//$vfile = DownloadYTVideo($video_id);
if (@file_exists($vfile)){
    echo "File: " . $vfile;
}else{ 
    @DebugOut($vfile,"Fehler");
}

$yt = new chopin2256\Youtube();  //Instantiate Youtube Object
$yt->key(YTAPIKEY);  //Set Youtube API Key Here

//Set Youtube Search Parameters
$yt->set()->
        q('ETW-FZ')->
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
DebugOut($results,"RESULTS");
echo "<hr>" . $link;
$yt->clear();  //Clear query string, extremely important if iterating through multiple keywords!


