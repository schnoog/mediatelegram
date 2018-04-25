#schnoog/mediatelegram

##https://github.com/schnoog/mediatelegram

#Telegram-Bot for videofiles download
- [x] Youtube (Video & mp3)
- [x] Twitter (Video)
- [x] Facebook (Video)
- [x] Misc. other video platforms (supported by youtube-dl)

##Requirements
- [x] php >= 5.5
- [x] Composer
- [x] MySQL-datebase
- [x] FFMpeg (needs to be executable by the webserver)
- [x] mp4box (needs to be executable by the webserver)
- [x] youtube-dl (needs to be executable by the webserver)
- [ ] Webserver with valid SSL certificate (for webhook)


##Installation
1.  Create the target directory and cd into it
2.  Clone this repo
`git clone https://github.com/schnoog/mediatelegram.git .`
3.  Install the composer depencies
`composer install`
4.  Import the structure.sql delivered with telegram-bot
`./vendor/longman/telegram-bot/structure.sql`
5.  Import the sql-Code mentioned in FirstSteps/Database_Setup.txt
`./FirstSteps/Database_Setup.txt`
6.  Create and edit the config.php
`cp include/config.php.dist include/config.php`

##Usage
###Manual call
Simply open the getUpdatesCLI.php
`php getUpdatesCLI.php`
or open it in you browser on your webserver
getUpdatesCLI.php
###Webhook
Open the sethook.php with supplied secret $Config['seccode'] in your browser
`https://YourBotsURL/sethook.php?secret=YourSecret`
This will install the webhook and every new command sent to the server will be processed

