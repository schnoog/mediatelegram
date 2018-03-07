<?php


function TelegramSendFiles($chat_id,$documents,$bolDeleteAfter = false){
    if(!$documents)return false;
    
    if(!is_array($documents)) {
        $docs[] = $documents;
    }else{
        $docs = $documents;
    }
    for($x=0;$x<count($docs);$x++){
        if($x >0) sleep (1);
        error_log("Uploading " . $docs[$x]);
        SendSpecDocToChat($chat_id,$docs[$x],$bolDeleteAfter);
    }
    
    
}


function TelegramTextMsg($chatID,$message){
    global $tg;
    $tg->sendMessage($chatID,$message);
    return true;
}

function TelegramWaitMsg($chatID){
    global $tg;
    $message = "Your request will be processed in a moment. Please wait....";
    $tg->sendMessage($chatID,$message);
    return true;
}

function TelegramBlockedMsg($chatID,$hoster = "Youtube"){
    global $tg;
    $message = "Sorry, " . $hoster . " blocked the download of the file";
    $tg->sendMessage($chatID,$message);
    return true;
}

function InlineKeyboardMarkup($keyboard)
 {
    $kb['inline_keyboard'] = $keyboard;
    return json_encode($kb);
 }
 
 
 function SendSpecDocToChat($chat_id,$document,$bolDeleteAfter = false){
    
    $curl = curl_init();
    $fn = basename($document);
    $ffn = urlencode($fn);
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.telegram.org/bot'.TELEGRAM_BOT_TOKEN.'/sendDocument?caption='.$ffn.'&chat_id=' . $chat_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: multipart/form-data'
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'document' => curl_file_create($document)
            ]
        ]);

        $ret = curl_exec($curl);
        curl_close($curl);
        $tmpx = json_decode($ret,true);
        $tmpx = (array)$tmpx;
        if($bolDeleteAfter){
            if($tmpx['ok'] == 1) unlink($document);
        }
        $msgid = $tmpx['result']['message_id']; 
        return $msgid;

}