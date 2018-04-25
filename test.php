<?php

include_once("bootstrap.php");

$url = 'https://twitter.com/EmrgencyKittens/status/989098281862094848';

$tmp = GetTwitterVideo($url);

Deb($tmp,"RETURN");













function GetTwitterVideoX($videoUrl){
    $videoUrl = escapeshellarg($videoUrl);
    //$videoUrl = urlencode($videoUrl);
    $erg=`youtube-dl --max-downloads 1 --restrict-filenames  $videoUrl`;
    echo $erg;
    $erglines = explode("\n",$erg);
    $ergline='';
    for($x=0;$x<count($erglines);$x++){
        if (strpos($erglines[$x],'has already been downloaded')) $ergline = $erglines[$x];
        if (strpos(" ". $erglines[$x],'[download] Destination:')) $ergline = $erglines[$x];
    }    
    
    return $ergline;
    
}