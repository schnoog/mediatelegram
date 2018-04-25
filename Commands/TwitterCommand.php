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
class TwitterCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'twitter';

    /**
     * @var string
     */
    protected $description = 'Download Twitter Video';

    /**
     * @var string
     */
    protected $usage = '/twitter <Twitter Video Url>';

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

            $singleSend =   Request::sendDocument([
                                    'caption'  => "Your file is ready",
                                    'chat_id'  => $chat_id,
                                    'document' => Request::encodeFile($sendfile),
                            ]);
            if(DELETE_AFTER) unlink($sendfile); 
            return $singleSend;
    }
}
