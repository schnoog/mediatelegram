<?php
/**
 * README
 * This file is intended to unset the webhook.
 * Uncommented parameters must be filled
 */
// Load composer
require_once(__DIR__ . "/bootstrap.php");

if (!isset($_GET['secret']) || $_GET['secret'] !== $Config['seccode']) {
    die("I'm safe =)");
}
// Add you bot's API key and name
$bot_api_key  = $Config['Telegram']['token'];
$bot_username = 'username_bot';
$hook_url = $Config['Hook']['url'];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    // Delete webhook
    $result = $telegram->deleteWebhook();
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    echo $e->getMessage();
}