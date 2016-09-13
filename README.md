# amitkhare/validbit
Validbit is an easy to use PHP validation library

## Install

Run this command from the directory in which you want to install.

### Via Composer:

    php composer.phar require amitkhare/validbit

### Via Git:

    git clone https://github.com/amitkhare/validbit.git

### Manual Install:

    Download: https://github.com/amitkhare/validbit/archive/master.zip
    Extract it, require "PATH-TO/"."validbit.php" where you want to use it.

## Usage:
```sh
<?php
use AmitKhare\ValidBit; // use namespace.

require("PATH-TO/"."validbit.php"); // only need to include if installed manually.

$v = new ValidBit(); // instantiate ValidBit;

//  OR with database for unique field check
$v = new ValidBit($host,$username,$password,$dbname); // instantiate ValidBit With Database features;

$v->setSource($_POST); // set data source array;

$v->check("mobile","required|numeric|min:10|max:15");
$v->check("username","required|alphanum|unique:users.username|min:4|max:20");
$v->check("email","required|email|unique:users.email|min:4|max:100");

if($v->isValid()){
    echo "PASS";
} else {
    print_r($v->getStatus());
}
```

## Available Rules:
    > required
    > email
    > url
    > numeric
    > string
    > float
    > ipv4
    > ipv6
    > bool
    > min
    > max
    > alphanum
    > alphanumUnicode
    > unique (avaiable only if instantiate ValidBit With Database);
