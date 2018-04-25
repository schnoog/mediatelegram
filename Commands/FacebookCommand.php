<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Request;

/**
 * User "/echo" command
 *
 * Simply echo the input back to the user.
 */
class FacebookCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'facebook';

    /**
     * @var string
     */
    protected $description = 'Download Facebook Video';

    /**
     * @var string
     */
    protected $usage = '/facebook <Facebook Video Url>';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $text    = trim($message->getText(true));

        if ($text === '') {
            $text = 'Command usage: ' . $this->getUsage();
        }

        $sendfile = GetTwitterVideo($text);
        if(!$sendfile){
            $data = [
                'chat_id' => $chat_id,
                'text'    => "No video file available, sorry",
            ];
            return Request::sendMessage($data);
        }
        $dlfiles = GetMediaChunks($sendfile,false);
        if(count($dlfiles)>0){
            for($x=0;$x<count($dlfiles);$x++){
                $file = $dlfiles[$x];
                $capt = "Get your file";
                if (count($dlfiles)>1) {
                    $num = $x + 1;
                    $capt .= "s" . "\n" . "Part " . $num . " of " . count($dlfiles);
                    }
                
                
                $singleSend =   Request::sendDocument([
                                        'caption'  => $capt,
                                        'chat_id'  => $chat_id,
                                        'document' => Request::encodeFile($file),
                                ]);
                if(DELETE_AFTER) unlink($file);                   
            }
            return $singleSend;
        }        
    






            return $singleSend;
    }
}
