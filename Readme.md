## NOTICES
I don't use getenv function instead of $_ENV because that function is not thread safe
By default the websocket server is working on: `localhost:8880` and webserwer is working on `localhost:8888`

## HOW TO USE IT
* get code from github :)
* `composer install`
* run php server: `composer run-script server-php`
* run websocket server: `composer run-script server-websocket`
* open browser (one or more tabs) on page: `http://localhost:8888`
* thats all

## PHP server
* `composer run-script server-php`

## Websocket server
* `composer run-script server-websocket`

## Testy
*  ./vendor/bin/phpunit --configuration phpunit.xml
