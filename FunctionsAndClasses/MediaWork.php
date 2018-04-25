<?php
/**
 * 
 * 
 * 
*/
function GetMediaChunks($mediafile,$AsMP3 = false){
    global $Config;
    
    $viddir = SPLITDIR;
    $out = array();
    $maxsizeB = $Config['chunks']['maxsize'] * 1024 * 1024;
    if($AsMP3){
        $mediafile = GetExtractedAudio($mediafile);
    }
    if (filesize($mediafile)< $maxsizeB){
        $out[] = $mediafile;
        return $out;
    }
    
    if(!$AsMP3){    
    
    $resize = $Config['chunks']['maxsize'] * 1000;
    $mpb = MP4BOX;
    $splitted = `$mpb -splits $resize "$mediafile" -out "$viddir"`;    
    if(DELETE_AFTER)unlink($mediafile);
    }

    if($AsMP3){
        $resfiles = SplitAudio($mediafile,filesize($mediafile),$maxsizeB);
        
    }

    $bn = basename($mediafile);
    $ans = substr($bn,0,-4);
    $len = strlen($ans);
        if ($handle = opendir($viddir)) {
            while (false !== ($entry = readdir($handle))) {
                if ( substr($entry,0,$len) == $ans ) {
                    $out[] = $viddir .$entry;
                }
            }
        closedir($handle);
        sort($out);        
        }
    return $out;
    
    
    
}

/**
 * GetExtractedAudio
 * Extracts the audio from given video file and returns the filename
 * 
*/

function GetExtractedAudio($videofile){   
        $audiofile =  VIDEODIR . basename($videofile,'.mp4' ). ".mp3";
        if (file_exists($audiofile)) return $audiofile;
    	$FFmpeg = new FFmpeg(FFMPEG);
    	$FFmpeg->input( $videofile )->output( $audiofile )->ready();
        if (file_exists($audiofile)) return $audiofile;
        return false;
}
/**
 * 
 * 
 * 
*/
function SplitAudio($mp3,$filesize,$max){
$mp3file = new MP3File($mp3);
$duration1 = $mp3file->getDuration();//(slower) for VBR (or CBR)
$chunks =  $filesize / $max;
$chunkduration = $duration1 / ceil($chunks);
$savechunkduration = ceil($chunkduration * 0.75);
echo "Duration:" . $duration1  ."\n";
echo "Chunks:" . $chunks . "\n";
echo "ChunkTime:" . $chunkduration.  "\n";
echo "SaveChunkTime" .$savechunkduration.  "\n";
$outname = substr($mp3,0,-4);
$outname .= '%03d.mp3';
$outname = str_replace(VIDEODIR,SPLITDIR,$outname);
$cmd = `FFMPEG -i "$mp3" -f segment -segment_time $savechunkduration -c copy "$outname"`;
}



















