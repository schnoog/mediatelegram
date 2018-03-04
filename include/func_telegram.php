<?php

function TelegramTextMsg($chatID,$message){
    global $tg;
    $tg->sendMessage($chatID,$message);
    return true;
}

public function InlineKeyboardMarkup($keyboard)
 {
    $by['inline_keyboard'] = $keyboard;
    return json_encode($kb);
 }