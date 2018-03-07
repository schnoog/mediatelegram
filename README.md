# MediaTelegram

> This bunch of scripts are made to build a quick and dirty Telegram Bot.
> Targets:
> - Find YouTube videos by search string or video id
> - Send the extracted audio or the complete video to the requester

#### Limitations
> Telegram has an 50MB filesize limit for any file sent by a bot

#### Mitigation
> Use of MP4Box to split videos biggerthan 50MB into smaller parts

## Requirements
- PHP CLI(I used 7.0) 
- FFMPEG
- MP4Box
- nohup

## Installation
> Clone this Repo, 
> run composer update, 
> copy the config.php.dist to config.php,
> set your YouTube-API-Key and your Telegram-Bot Token in the config.php

## Run it
> run telegram_loop.sh

















