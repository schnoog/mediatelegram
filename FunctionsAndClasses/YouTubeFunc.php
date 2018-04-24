<?php



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