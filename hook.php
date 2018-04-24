<?php
// Load composer
require_once(__DIR__ . "/bootstrap.php");


$commands_paths = [
  __DIR__ . '/Commands/',
];

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($Config['Telegram']['token'], $Config['Telegram']['botname']);
    // Enable MySQL
    $telegram->enableMySql($Config['DB']['telegram']);
    $telegram->enableLimiter();
    $telegram->addCommandsPaths($commands_paths);
    $telegram->enableAdmin($Config['AdminID']);
    $telegram->setDownloadPath(__DIR__ . '/Download');
    $telegram->setUploadPath(__DIR__ . '/Upload');    
    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e->getMessage();
}