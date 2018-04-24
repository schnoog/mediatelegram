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
                        $ilke[] = ['text' => $files[$x]['format'],];
                        Deb($files[$x],"File: $x");
                    }

                    $inline_keyboard = new InlineKeyboard($ilke);
                            $data = [
                                        'chat_id'      => $chat_id,
                                        'text'         => $out,
                                        'reply_markup' => $inline_keyboard,
                                    ];
                    return Request::sendMessage($data);
                break;
            
            
            
            
            
        }





        return Request::sendMessage($data);
    }
}
