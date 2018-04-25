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
class HelpCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'help';

    /**
     * @var string
     */
    protected $description = 'Show the help';

    /**
     * @var string
     */
    protected $usage = '/help';

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

        $tx[] = "MediaTelegram";
        $tx[] = "_Functions_";
        $tx[] = "Easy Video/Audio download from YouTube";
        $tx[] = '*Available commands*';
        $tx[] = "/help ...hmmm let's guess";
        $tx[] = "/youtube <searchterm>  Search Youtube video"; 

        $text = implode("\n",$tx);

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
            'parse_mode' => 'MARKDOWN',
            
        ];

        return Request::sendMessage($data);
    }
}
