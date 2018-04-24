<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 *
 * This command handles all callback queries sent via inline keyboard buttons.
 *
 * @see InlinekeyboardCommand.php
 */
class CallbackqueryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.1.1';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $callback_query    = $this->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data     = trim($callback_query->getData());
        $callback_caller  = $callback_query->getFrom();
        $callerid = $callback_caller->getId();
//Deb($callback_query,"callback_query");
//Deb($callerid,"caller");
        
/*        if (strlen($callback_data)>0){
                $cmd = $callback_data;    
                if (strpos($callback_data," "))list($cmd,$para) = explode(" ",$callback_data);
            fWorkWithCallbackQuery($callback_data,$callerid);     
        }
*/
 $callback_query    = $this->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data     = $callback_query->getData();
        $cmd = $callback_data;
        $para = "";    
                if (strpos($callback_data," "))list($cmd,$para) = explode(" ",$callback_data,2); 
                $update = (array) $this->update;
                $update['message'] = $update['callback_query']['message'];
                $update['message']['from']['id']=$update['callback_query']['from']['id'];
                $utext = '/' . $cmd ;
                if (strlen($para)>0) $utext .=   " " . $para;
                $update['message']['text'] = $callback_data;
//echo "Callback_data: " . $callback_data . "\n";                
//echo "CMD: $cmd" . "\n";
$cmd = str_replace("/","",$cmd);               
        switch ($cmd){
            case 'help':
                return (new \Longman\TelegramBot\Commands\UserCommands\HelpCommand($this->telegram, new \Longman\TelegramBot\Entities\Update($update)))->preExecute();
                //return (new \Longman\TelegramBot\Commands\UserCommands\MenueCommand($this->telegram, new \Longman\TelegramBot\Entities\Update($update)))->preExecute();
                break;
                                 
        }

        if(substr($cmd,0,14) == 'command=fruits'){
                return (new \Longman\TelegramBot\Commands\UserCommands\FruitsCommand($this->telegram, new \Longman\TelegramBot\Entities\Update($update)))->preExecute();
        }

        if(substr($cmd,0,10) == 'rlcommand='){
                return (new \Longman\TelegramBot\Commands\UserCommands\RanglisteCommand($this->telegram, new \Longman\TelegramBot\Entities\Update($update)))->preExecute();
        }

        if(substr($cmd,0,10) == 'trcommand='){
                return (new \Longman\TelegramBot\Commands\UserCommands\TourenCommand($this->telegram, new \Longman\TelegramBot\Entities\Update($update)))->preExecute();
        }        

//        if ($callback_data)

       // return Request::answerCallbackQuery($data);
    }
}