<?php

/**
 * 
 * 
*/

/**
 * GetExtractedAudio
 * Extracts the audio from given video file and returns the filename
 * 
*/

function GetExtractedAudio($videofile){   
        $audiofile =  VIDEODIR . basename($videofile,'.mp4' ). ".mp3";
    	$FFmpeg = new FFmpeg(FFMPEG);
    	$FFmpeg->input( $videofile )->output( $audiofile )->ready();
        if (file_exists($audiofile)) return $audiofile;
        return false;
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
