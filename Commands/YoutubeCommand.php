<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Request;
use \Longman\TelegramBot\Entities\InlineKeyboard;
use \Longman\TelegramBot\Entities\InlineKeyboardButton;
use TelegramBot\InlineKeyboardPagination\Exceptions\InlineKeyboardPaginationException;
use TelegramBot\InlineKeyboardPagination\InlineKeyboardPagination;

class YoutubeCommand extends UserCommand
{
    protected $name = 'youtube';
    protected $description = 'Show the results.';
    protected $usage = '/youtube <searchterm>';
    protected $version = '1.0.0';
    protected static $per_page = 8;
    public $ownmessage;
    public $service = "";


    public static function callbackHandler($text,$message)
    {
        $params = InlineKeyboardPagination::getParametersFromCallbackData($text);
        if ($params['command'] !== 'youtube') {
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
        if ($pagination = self::getInlineKeyboardPagination($params['newPage'],$message,$params['sid'])) {
            $data['text']         = 'Your results' ;
            $items = $pagination['items'];
            Deb(array($items),"Pagination Page" . $params['newPage']);
            for($x=0;$x<count($items);$x++){
               $lbl = $items[$x]['label'];
               $cb  = $items[$x]['callback'];
               $sub[] = [['text' => $lbl,'callback_data' => $cb]];
            }

            $sub[] = $pagination['keyboard'];           
            $data['reply_markup'] = [ 'inline_keyboard' => $sub ];            
        }

        return Request::editMessageText($data);
    }

    public static function getResults($searchID)
    {
                $datax =  GetYoutubeResultByID($searchID);
                        for($x=0;$x<count($datax);$x++){
                            $data = $datax[$x];
                            $txt = mdescape($data['title']) ;
                            $out[$x]['label'] = $txt;
                            $out[$x]['callback'] = "mediadetail Youtube " . $data['id']; 
                        }                
                
        Deb($out,"Results");
        return $out;
    }



    public static function getInlineKeyboardPagination($page = 1,$message,$searchid)
    {
        $results   = self::getResults($searchid);
        if (empty($results)) {
            $chat_id = $message->getChat()->getId();
            $text    = "No Youtube Video found, sorry";
            
            Deb("NO RESULTS");
            return Request::sendMessage($data);
        }
        
        // Define inline keyboard pagination.
        $ikp = new InlineKeyboardPagination($results, 'youtube', $page, self::$per_page);

        $callback_data_format = 'command={COMMAND}&oldPage={OLD_PAGE}&newPage={NEW_PAGE}&sid=' . $searchid;
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
        
        
        if(substr($text,0,strlen('command=youtube')) == 'command=youtube'){
            $this->callbackHandler($text,$message);
            return true;
        }

        $searchID = GetYoutubeSearchID(trim($text));
        if(!$searchID){
             $predata = GetYoutubeSearchResults(trim($text));
             $searchID = GetYoutubeSearchID(trim($text));
        }        
        
        
        
        
        
        $data = [
            'chat_id' => $chat_id,
            'text'    => 'Empty',
        ];

        if ($pagination = self::getInlineKeyboardPagination(1,$message,$searchID)) {
            $data['text'] = 'Your search results'; 
            $items = $pagination['items'];
           // Deb($items,"ITEMS");
            
            for($x=0;$x<count($items);$x++){
               $lbl = $items[$x]['label'];
               $cb  = $items[$x]['callback'];
               $sub[] = [['text' => $lbl,'callback_data' => $cb]];
            }
            $lbl = "\u{1F310}" . " Back to main menue";
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