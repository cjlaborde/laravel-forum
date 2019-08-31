# Laravel Forum [![Build Status](https://travis-ci.org/cjlaborde/laravel-forum.svg?branch=master)](https://travis-ci.org/cjlaborde/laravel-forum)

This is an open source forum that was build with laravel php framework

### Step 1	### Prerequisites

 * To run this project, you must have PHP 7 installed.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet. 
* If you want use Redis as your cache driver you need to install the Redis Server. You can either use homebrew on a Mac or compile from source (https://redis.io/topics/quickstart). 


## Installation

### Step 1.

Begin by cloning this repository to your machine, and installing all Composer dependencies.

```bash
git clone git@github.com:JeffreyWay/council.git
cd council && composer install
php artisan key:generate
mv .env.example .env
```

### Step 2.

Next, create a new database and reference its name and username/password within the project's `.env` file. In the example below, we
've named the database "forum".

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forum
DB_USERNAME=root
DB_PASSWORD=
```

Then, migrate your database to create tables.

Set admin in config/forum.php

`php artisan migrate`

`php artisan db:seed`





### Step 3.

reCAPTCHA is a Google tool to help prevent forum spam. You'll need to create a free account (don't worry,it's quick).
> https://www.google.com/recaptcha/intro/v3.html

Choose reCAPTCHA V2, and specify your local (and eventually production) domain name, as illustrated in the image below.

![alt text](https://i.imgur.com/l5RD48w.png "Google reCAPTCHA instructions")

Once submitted, you'll see two important keys that should be referenced in your .env file.

```
RECAPTCHA_KEY=PASTE_KEY_HERE
RECAPTCHA_SECRET=PASTE_SECRET_HERE
```

### Step 4.

Step 2.
Until an administration portal is available, manually insert any number of "channels" (think of these as forum categories) into the "channels" table in your database.

Visit: http://forum.test/register and register an account.
Edit config/forum.php, adding the email address of the account you just created.
Visit: http://forum.test/admin/channels and add at least one channel.

`php artisan cache:clear`



### Step 5.

Use your forum! Visit http://forum.test/threads to create a new account and publish your first thread after verifying your email.


Profile image link public with storage


ln -s "/home/cjlaborde/Documents/Laravel/Laravel-Projects/git-hub-projects/laravel-forum/storage/app/public/avatars/" "/home/cjlaborde/Documents/Laravel/Laravel-Projects/git-hub-projects/laravel-forum/public/"

#### Enviroment
https://travis-ci.org
https://github.styleci.io

