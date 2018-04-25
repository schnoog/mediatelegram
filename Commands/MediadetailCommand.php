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
class MediadetailCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'mediadetail';

    /**
     * @var string
     */
    protected $description = 'Shows media details';

    /**
     * @var string
     */
    protected $usage = '/mediadetail <Service> <ID>';

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
        $chat_id = $this->getMessage()->getChat()->getId();
        $message = $this->getMessage();
        $text    = trim($message->getText(true));
        Deb($text,"TETX");
        list($cmd,$service,$mediaid) = explode(" ",$text,3);
        $out = "Result";
        switch($service){
            case "Youtube":
                    $files = GetYoutubeFiles($mediaid);
                    $out = mdescape(get_youtube_title($mediaid));
                    for($x=0;$x<count($files);$x++){
                        $ilkeE[] = [['text' => $files[$x]['format'],'callback_data' => 'mediadownload '. $x ." ". $service . " " . $mediaid]];
                        $tmp = new InlineKeyboardButton(['text' => $files[$x]['format'],'callback_data' => 'mediadownload '. $x ." ". $service . " " . $mediaid]);
                        $ilke[] = $tmp;
                        //Deb($files[$x],"File: $x");
                    }
                    if(count($files)>0){
                        $tmp = new InlineKeyboardButton(['text' => 'MP3 Audio','callback_data' => 'mediadownload mp3 '. $service . " " . $mediaid]);
                        $ilke[] = $tmp;
                        
                    }

                    $image = get_youtube_thumb($mediaid);
                             $data = [
                                        'chat_id'      => $chat_id,
                                        'caption'         => $out,
                                        'photo' => $image,
                                    ];                   
                    $det = Request::sendPhoto($data);
                    $out = "Downloads";

                    $max_per_row  = 2; // or however many you want!
                    $per_row      = sqrt(count($ilke));
                    $rows         = array_chunk($ilke, $per_row === floor($per_row) ? $per_row : $max_per_row);
                    $reply_markup = new InlineKeyboard(...$rows);
                            $data = [
                                        'chat_id'      => $chat_id,
                                        'text'         => $out,
                                        'reply_markup' => $reply_markup,
                                        'one_time_keyboard' => true,
                                    ];
                    return Request::sendMessage($data);
                break;
            case "TeleTubbyTV":
            
                break;            
            
            
            
            
        }





        return Request::sendMessage($data);
    }
}
