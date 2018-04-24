<?php
// Load composer
require_once(__DIR__ . "/bootstrap.php");
if (!isset($_GET['secret']) || $_GET['secret'] !== $Config['seccode']) {
    die("I'm safe =)");
}
$bot_api_key  = $Config['Telegram']['token'];
$bot_username = 'username_bot';
$hook_url = $Config['Hook']['url'];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}