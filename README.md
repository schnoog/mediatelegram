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
###Get the files
#####Composer only
`composer create-project schnoog/mediatelegram`
#####Git & Composer
-Create the target directory and cd into it
-Clone this repo
`git clone https://github.com/schnoog/mediatelegram.git .`
-Install the composer depencies
`composer install`

###Prepare & Config

1.  Import the structure.sql delivered with telegram-bot
`./vendor/longman/telegram-bot/structure.sql`
2.  Import the sql-Code mentioned in FirstSteps/Database_Setup.txt
`./FirstSteps/Database_Setup.txt`
3.  Create and edit the config.php
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

