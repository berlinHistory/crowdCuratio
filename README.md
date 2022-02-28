# **Berlin Crowd Curatio**

## Installation

### 1. Install composer 

Run these cmds:
   
* php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
* php composer-setup.php
* php -r "unlink('composer-setup.php');"
* composer require spatie/laravel-translatable
  /opt/plesk/php/8.0/bin/php composer.phar install --no-ansi --no-interaction --no-progress --optimize-autoloader --no-scripts --working-dir=./
### 2. Install dependencies

Run this:

* php composer.phar install --no-ansi --no-interaction --no-progress --optimize-autoloader --no-scripts --working-dir=%path%

### 3. Migrate Database

Run this:

* php artisan migrate

### 4. Seed Database

Locate the file: database/seeders/CreateAdminUserSeeder.php

Set up the Admin info
````
'name' => 'name_and_surname_admin',
'email' => 'email_admin',
'password' => bcrypt('password_admin')
````
Run: 

* php artisan db:seed


Then remove the password from the code because of safety
## Database 

Via phpMyAdmin: http://localhost:8080/

```
username: dev

password: dev
```
## Notification Email

In env.

```
MAIL_MAILER=smtp 

MAIL_HOST=smtp.googlemail.com

MAIL_PORT=587

MAIL_USERNAME=user_email

MAIL_PASSWORD=user_password

MAIL_ENCRYPTION=tls

MAIL_FROM_ADDRESS=user_email_from

MAIL_FROM_NAME=Crowd_Curatio
```

