<?php

namespace chopin2256;

require_once(__DIR__ ."/Youtube_Base.php");

class Youtube_Get extends Youtube_Base {
    
    /**
     * 
     * @Desc Obtains Youtube videoId
     */
    public function alldata(){
        $datas = $this->_getArrayData();
//        DebugOut($datas,"DATAS");

        return $datas;
    }

    public function thumbnail(){
        $datas = $this->_getArrayData();
//        DebugOut($datas,"DATAS");
        $i = 0;
        foreach ($datas['items'] as $values) {            
            foreach ($values['snippet']['thumbnails'] as $k => $v) {
                if ($k == 'default') {
                    $result[$i] = $v;
                    $i++;
                }                 
            }
        }
        return $result;
    }    
    
    public function id(){
        $datas = $this->_getArrayData();
//        DebugOut($datas,"DATAS");
        $i = 0;
        foreach ($datas['items'] as $values) {            
            foreach ($values['id'] as $k => $v) {
                if ($k == 'videoId') {
                    $result[$i] = $v;
                    $i++;
                }                 
            }
        }
        return $result;
    }
    
    /**
     * 
     * @Desc Obtains Youtube video title
     */
    public function title(){
        $datas = $this->_getArrayData();
        
        $i = 0;
        foreach ($datas['items'] as $values) {            
            foreach ($values['snippet'] as $k => $v) {
                if ($k == 'title') {
                    $result[$i] = $v;
                    $i++;
                }                 
            }
        }
        return $result;
    }
}
