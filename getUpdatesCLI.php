#!/usr/bin/env php
<?php

error_reporting(E_ALL);

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
    $telegram->enableAdmin(549279974);
    $telegram->setDownloadPath(__DIR__ . '/Download');
    $telegram->setUploadPath(__DIR__ . '/Upload');    
    
    // Handle telegram getUpdates request
//    CallbackqueryCommand::addCallbackHandler([NachweiseCommand::class, 'callbackHandler']);
    $server_response = $telegram->handleGetUpdates(2,2);
    
    if ($server_response->isOk()) {
        $update_count = count($server_response->getResult());
        $tmps =  date('Y-m-d H:i:s', time()) . ' - Processed ' . $update_count . ' updates';
        Deb($server_response,$tmps);

    } else {
        $tmps = date('Y-m-d H:i:s', time()) . ' - Failed to fetch updates' . PHP_EOL;
        Deb($server_response,$tmps);
        echo $server_response->printError();
    }    
    
    
    
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
     $tx = $e->getMessage();
     Deb($tx,"ERRORS");
}