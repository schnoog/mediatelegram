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
use Longman\TelegramBot\Entities\InlineKeyboard;
use \Longman\TelegramBot\Entities\InlineKeyboardButton;

use Longman\TelegramBot\Request;

/**
 * User "/inlinekeyboard" command
 *
 * Display an inline keyboard with a few buttons.
 */
class MediadownloadCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'mediadownload';

    /**
     * @var string
     */
    protected $description = 'Shows media details';

    /**
     * @var string
     */
    protected $usage = '/mediadownload <download-index or MP3> <Service> <ID>';

    /**
     * @var string
     */
    protected $version = '0.1.0';

    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $hasdownload = false;
        $chat_id = $this->getMessage()->getChat()->getId();
        $message = $this->getMessage();
        $text    = trim($message->getText(true));
        Deb($text,"TETX");
        list($cmd,$selectedfile,$service,$mediaid) = explode(" ",$text,4);
        $out = "Result";
        $dlfiles = array();
        switch($service){
            case "Youtube":
                    
                    $out = mdescape(get_youtube_title($mediaid));
                    $selIndex = $selectedfile;
                    $asmp3 = false;
                    if($selectedfile == "mp3"){
                        $selIndex = 0;
                        $asmp3 = true;
                    }
                    $dlrow = DownloadYTVideo($mediaid,$selIndex);
                    $dlfiles = GetMediaChunks($dlrow,$asmp3);
                    Deb($dlfiles,"DLROW");

                break;
        }

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



        if($hasdownload){
            Request::sendMessage([
                'chat_id'      => $chat_id,
                'text'         => '..........',
                'reply_markup' => Keyboard::remove(),
            ]);
        }

    }
}
