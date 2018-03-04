<?php
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
