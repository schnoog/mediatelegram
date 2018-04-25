<?php

/**
 * 
 * 
 * 
 * 
 * 
 */



function DownloadYTVideo($video_id,$selectedIndex){
   $yt = new YouTubeDownloader();
   $videolist = $yt->getDownloadLinks("https://www.youtube.com/watch?v=" .$video_id,"mp4");
   $video['file'] = $videolist[$selectedIndex]['url'] ;
   $video['title'] = get_youtube_title($video_id);
   $video['filename'] =  preg_replace( '/[^a-z0-9]+/', '-', strtolower( $video['title'] ) ) . ".mp4"; 
   if (substr($video['filename'],0,1)== "-") $video['filename'] = substr($video['filename'],1);
   $vidfile = VIDEODIR . $video['filename'];
   $viddir = VIDEODIR . "splitted/";
   if(!file_exists($vidfile))  file_put_contents($vidfile , fopen($video['file'], 'r'));
   if (file_exists($vidfile))return $vidfile;
   return false;
}

/**
 * 
 * 
 * 
 * 
 * 
 */
function GetYoutubeFiles($video_id){
       $yt = new YouTubeDownloader();
       $videolist = $yt->getDownloadLinks("https://www.youtube.com/watch?v=" .$video_id,"mp4");
       return $videolist;
}
/**
 * 
 * 
 * 
 * 
 * 
 */
function GetYoutubeSearchID($searchstring){
    global $Config;
    $searchhash = md5("Youtube" . $searchstring);
    return DB::queryFirstField("Select id from mediaposter_searchcache WHERE searchhash = %s AND searchtimestamp > %i ORDER by id",$searchhash, time()-$Config['api']['youtube']['maxage']);
}
/**
 * 
 * 
 * 
 * 
 * 
 */
function GetYoutubeResultByID($id){
    $res = DB::queryFirstRow("Select * from mediaposter_searchcache WHERE id = %i",$id);
    if($res){
        $out = json_decode($res['searchresult'],true);
     return $out;
    }
}

/**
 * 
 * 
 * 
 * 
 * 
 */
function GetYoutubeSearchResults($searchstring){
    global $Config;
    $searchhash = md5("Youtube" . $searchstring);
    $res = DB::queryFirstRow("Select * from mediaposter_searchcache WHERE searchhash = %s AND searchtimestamp > %i ORDER by id",$searchhash, time()-$Config['api']['youtube']['maxage']);
    if($res){
        $out = json_decode($res['searchresult'],true);
     return $out;
    }
    $results = SearchYoutube($searchstring,50);
    if(count($results)== 0) return false;
    for ($x=0;$x<count($results);$x++){
        $results[$x]['thumb'] = get_youtube_thumb($results[$x]['id']);
        
    }
    $out = json_encode($results);
    DB::query("Delete from mediaposter_searchcache WHERE searchhash = %s",$searchhash);
    DB::query("Insert into mediaposter_searchcache (service,searchstring,searchhash,searchresult,searchtimestamp) VALUES (%s,%s,%s,%s,%i)",'Youtube',$searchstring,$searchhash,$out,time());
    return json_decode($out,true);
}




/**
 * 
 * 
 * 
 * 
 * 
 */
function get_youtube_title($youtube_videoid) {
  $json = file_get_contents('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' . $youtube_videoid . '&format=json'); //get JSON video details
  $details = json_decode($json, true); //parse the JSON into an array
  return $details['title']; //return the video title
}

function get_youtube_thumb($youtube_videoid) {
  $out = "https://i.ytimg.com/vi/$youtube_videoid/hqdefault.jpg";
  return $out; //return the video title
}


function SearchYoutube($querystring,$searchnum=10){
    global $Config;
    if($searchnum>50)$searchnum=50;
$results = array();    
$yt = new chopin2256\Youtube();  //Instantiate Youtube Object
$yt->key($Config['api']['youtube']['key']);  //Set Youtube API Key Here
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