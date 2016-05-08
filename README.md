# amitkhare/validbit
Validbit is an easy to use PHP validation library

# Install

Run this command from the directory in which you want to install.

Via Composer:

    php composer.phar require amitkhare/validbit

Via Git:

    git clone https://github.com/amitkhare/validbit.git

Manual Install:

    Download: https://github.com/amitkhare/validbit/archive/master.zip
    Extract it, require "PATH-TO/"."validbit.php" where you want to use it.

# Usage:
    
    <?php
        use AmitKhare\ValidBit; // use namespace.
        
        require("PATH-TO/"."validbit.php"); // only need to include if installed manually.
        
        $v = new ValidBit(); // instantiate ValidBit;

        $v->setSource($_POST); // set data source array;
        
        $v->check("username","required|string|min:4|max:10");
        $v->check("email","required|email");
        $v->check("mobile","required|numeric|min:4|max:10");
        
        if($v->isValid()){
        	echo "PASS";
        } else {
            print_r($v->getStatus());
        }


# Available Rules:
    > required
    > email
    > url
    > numeric
    > string
    > float
    > ipv4
    > ipv6
    > bool