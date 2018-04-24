<?php
/**
 * 
 * 
 * 
 * 
 */

function Deb($data,$label=""){
    global $nl, $cli;
    $out = '';
    if (strlen($label)>0){
        if($cli){
            $out .= "****" . $label . "****" . $nl;
        }else{
            $out .= "<h2>" . $label . "</h2>";
        }
    } 
    
    $tmp = print_r($data,true);
    if ($cli) $tmp = br2nl($tmp);
    if (!$cli) $tmp = "<pre>" . $tmp . "</pre>";
    $out .= $tmp . $nl;
    echo $out;
    return $out;
    
}
/**
 * 
 * 
 * 
 * 
 */
function mdescape($string){
    $toescape = array('*',"'",'_','[',']','`');
    $string = str_replace($toescape,'',$string);    
    $toescape = array('<div>','</div>','<br>','<hr>','<hr />');
    $string = str_replace($toescape,"\n",$string); 
    
    
    return $string; 
}

/**
 * 
 * 
 * 
 * 
 */
function br2nl($string){
    return preg_replace('#<br\s*?/?>#i', "\n", $string);  
}
/**
 * 
 * 
 * 
 * 
 */