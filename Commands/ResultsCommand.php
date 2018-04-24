<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Request;
use \Longman\TelegramBot\Entities\InlineKeyboard;
use \Longman\TelegramBot\Entities\InlineKeyboardButton;
use TelegramBot\InlineKeyboardPagination\Exceptions\InlineKeyboardPaginationException;
use TelegramBot\InlineKeyboardPagination\InlineKeyboardPagination;

class ResultsCommand extends UserCommand
{
    protected $name = 'results';
    protected $description = 'Show the results.';
    protected $usage = '/results <service> <searchterm>';
    protected $version = '1.0.0';
    protected static $per_page = 8;
    public $ownmessage;



    public static function callbackHandler($text,$message)
    {
        $params = InlineKeyboardPagination::getParametersFromCallbackData($text);
        if ($params['command'] !== 'results') {
            return null;
        }
        $message_id = $message->getMessageId();
        $chat_id = $message->getChat()->getId();
        $data = [
            'chat_id'    => $chat_id,
            'message_id' => $message_id,
            'text'       => 'Empty',
        ];

        // Using pagination
        if ($pagination = self::getInlineKeyboardPagination($params['newPage'],$message)) {
            $data['text']         = 'Your results' ;
            $items = $pagination['items'];
            //$pagination['keyboard'][]= (array)(new InlineKeyboardButton(['text' => "blabla" , 'callback_data' => "bliblablub"]));
            for($x=0;$x<count($items);$x++){
               $lbl = $items[$x]['label'];
               $cb  = $items[$x]['callback'];
               $sub[] = [['text' => $lbl,'callback_data' => $cb]];
            }

            $lbl = "\u{1F310}" . " Zurück zum Hauptmenü";
            $cb = "menue";
            $sub[] = [['text' => $lbl,'callback_data' => $cb]];
            $sub[] = $pagination['keyboard'];           
            $data['reply_markup'] = [ 'inline_keyboard' => $sub ];            
        }

        return Request::editMessageText($data);
    }

    public static function getResults($message)
    {
        $out = array();
        $user    = $message->getFrom();
        $user_id = $user->getId();
        $text   = $message->getText(true);
        list($service,$serachstring) = explode(" ",$text,2);
        switch ($service){
            case "Youtube":
                $datax =  GetYoutubeSearchResults($serachstring);
                        for($x=0;$x<count($datax);$x++){
                            $data = $datax[$x];
                            $txt = mdescape($data['title']) ;
                            $out[$x]['label'] = $txt;
                            $out[$x]['callback'] = "mediadetail ". $service ." " . $data['id']; 
                        }                
                break;
            case "TeleTubbyTV":
            
                break;            
            
        }
        return $out;
    }



    public static function getInlineKeyboardPagination($page = 1,$message)
    {
        $results   = self::getResults($message);
        if (empty($results)) {
            Deb("NO RESULTS");
            return null;
        }
        // Define inline keyboard pagination.
        $ikp = new InlineKeyboardPagination($results, 'results', $page, self::$per_page);
        $callback_data_format = 'command={COMMAND}&oldPage={OLD_PAGE}&newPage={NEW_PAGE}';
        $ikp->setCallbackDataFormat($callback_data_format);
        // If item count changes, take wrong page clicks into account.
        try {
            $pagination = $ikp->getPagination();
        } catch (InlineKeyboardPaginationException $e) {
            $pagination = $ikp->getPagination(1);
        }
        return $pagination;
    }

    public function execute()
    {
        $message = $this->getMessage();
        $this->ownmessage = $message;
        $chat_id = $message->getChat()->getId();
        $text    = trim($message->getText(true));
        if(substr($text,0,15) == 'command=results'){
            $this->callbackHandler($text,$message);
            return true;
        }


        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Empty',
        ];

        if ($pagination = self::getInlineKeyboardPagination(1,$message)) {
            $data['text'] = 'Deine Touren'; 
            $items = $pagination['items'];
            Deb($items,"ITEMS");
            
            for($x=0;$x<count($items);$x++){
               $lbl = $items[$x]['label'];
               $cb  = $items[$x]['callback'];
               $sub[] = [['text' => $lbl,'callback_data' => $cb]];
            }
            $lbl = "\u{1F310}" . " Zurück zum Hauptmenü";
            $cb = "menue";
            $sub[] = [['text' => $lbl,'callback_data' => $cb]];
            $sub[] = $pagination['keyboard'];    
            Deb($sub,"SUB");       
            $data['reply_markup'] = [ 'inline_keyboard' => $sub ];
            $data['parse_mode'] = 'MARKDOWN';
        }
        return Request::sendMessage($data);
    }
}