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
    public $service = "";


    public static function callbackHandler($text,$message,$service)
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
        if ($pagination = self::getInlineKeyboardPagination($params['newPage'],$message,$service,$params['searchterm'])) {
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

    public static function getResults($service,$searchtermB64)
    {
        Deb(array($service,$searchtermB64,urldecode($searchtermB64)),"Results input text");
        $searchterm = urldecode($searchtermB64);
        switch ($service){
            case "Youtube":
                $datax =  GetYoutubeSearchResults($searchterm);
                
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
        Deb($out,"Results");
        return $out;
    }



    public static function getInlineKeyboardPagination($page = 1,$message,$service,$searchtermB64)
    {
        $results   = self::getResults($service,$searchtermB64);
        if (empty($results)) {
            Deb("NO RESULTS");
            return null;
        }
        Deb($service,"ILP Service");
        
        // Define inline keyboard pagination.
        $ikp = new InlineKeyboardPagination($results, 'results', $page, self::$per_page);

        $callback_data_format = 'command={COMMAND}&oldPage={OLD_PAGE}&newPage={NEW_PAGE}&service=' . $service . '&searchterm=' .$searchtermB64;
        Deb($callback_data_format,'$callback_data_format');
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
        
        Deb($text,"Execute input text");
        if (stripos(" ". $text,"youtube")) $service = "Youtube";
        if(substr($text,0,15) == 'command=results'){
            $this->callbackHandler($text,$message,$service);
            return true;
        }

        $text   = str_replace("/results ","",$text);
        $text   = str_replace("/youtube","Youtube",$text);
        list($service,$serachstring) = explode(" ",$text,2);        
        
        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Empty',
        ];

        if ($pagination = self::getInlineKeyboardPagination(1,$message,$service,urlencode($serachstring))) {
            $data['text'] = 'Deine Touren'; 
            $items = $pagination['items'];
           // Deb($items,"ITEMS");
            
            for($x=0;$x<count($items);$x++){
               $lbl = $items[$x]['label'];
               $cb  = $items[$x]['callback'];
               $sub[] = [['text' => $lbl,'callback_data' => $cb]];
            }
            $lbl = "\u{1F310}" . " Zurück zum Hauptmenü";
            $cb = "menue";
            $sub[] = [['text' => $lbl,'callback_data' => $cb]];
            $sub[] = $pagination['keyboard'];    
            //Deb($sub,"SUB");       
            $data['reply_markup'] = [ 'inline_keyboard' => $sub ];
            $data['parse_mode'] = 'MARKDOWN';
        }
        return Request::sendMessage($data);
    }
}