<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Request;
use TelegramBot\InlineKeyboardPagination\Exceptions\InlineKeyboardPaginationException;
use TelegramBot\InlineKeyboardPagination\InlineKeyboardPagination;

class FruitsCommand extends UserCommand
{
    protected $name = 'fruits';
    protected $description = 'Display fruits, with inline pagination.';
    protected $usage = '/fruits';
    protected $version = '1.0.0';
    protected static $per_page = 3;
    
    /** @var array Fruits to display inline (can also be dynamically generated) */
    public static $fruits = [
        'apple'         => ['id' => 1, 'name' => 'Apple', 'message' => 'Mmhhh, delicious'],
        'orange'        => ['id' => 2, 'name' => 'Orange', 'message' => 'Mmhhh, delicious'],
        'cherry'        => ['id' => 3, 'name' => 'Cherry', 'message' => 'Mmhhh, delicious'],
        'banana'        => ['id' => 4, 'name' => 'Banana', 'message' => 'Mmhhh, delicious'],
        'mango'         => ['id' => 5, 'name' => 'Mango', 'message' => 'Mmhhh, delicious'],
        'passion_fruit' => ['id' => 6, 'name' => 'Passion fruit', 'message' => 'Mmhhh, delicious'],
    ];

    public static function callbackHandler($text,$message)
    {
        
        Deb($message,"message");
        $params = InlineKeyboardPagination::getParametersFromCallbackData($text);
        if ($params['command'] !== 'fruits') {
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
        if ($pagination = self::getInlineKeyboardPagination($params['newPage'])) {
            $data['text']         = self::getPaginationContent($pagination['items']);
            $data['reply_markup'] = [
                'inline_keyboard' => [$pagination['keyboard']],
            ];
        }

        return Request::editMessageText($data);
    }

    public static function getFruits()
    {
        return self::$fruits;
//        return DB::getPdo()->query('SELECT * FROM `fruits`')->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPaginationContent(array $items)
    {
        $text = '';

        foreach ($items as $row) {
            $text .= "id: {$row['id']}\n";
            $text .= "name: {$row['name']}\n";
            $text .= "message: {$row['message']}\n";
        }

        return $text;
    }

    public static function getInlineKeyboardPagination($page = 1)
    {
        $fruits   = self::getFruits();

        if (empty($fruits)) {
            return null;
        }

        // Define inline keyboard pagination.
        $ikp = new InlineKeyboardPagination($fruits, 'fruits', $page, self::$per_page);

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
        $data = [
            'chat_id' => $this->getMessage()->getChat()->getId(),
            'text'    => 'Empty',
        ];
        $message = $this->getMessage();
        $this->ownmessage = $message;
        $chat_id = $message->getChat()->getId();        
        $text    = trim($message->getText(true));
        if(substr($text,0,14) == 'command=fruits'){
            $this->callbackHandler($text,$message);
            return true;
        }        

        if ($pagination = self::getInlineKeyboardPagination(1)) {
            $data['text']         = self::getPaginationContent($pagination['items']);
            $data['reply_markup'] = [
                'inline_keyboard' => [$pagination['keyboard']],
            ];
        }

        return Request::sendMessage($data);
    }
}