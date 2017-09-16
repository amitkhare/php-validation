<?php
    use AmitKhare\ValidBit; // use namespace.
    // autoload via composer
    require __DIR__.'/../vendor/autoload.php';
    // OR
    // require("PATH-TO/"."validbit.php"); // only need to include if installed manually.
    
    $v = new ValidBit(); // instantiate ValidBit;
	//  OR with database for unique field check
    $v = new ValidBit($host,$username,$password,$dbname); // instantiate ValidBit With Database features;
    $v->setSource($_POST); // set data source array;
    
    $v->check("mobile","required|numeric|min:10|max:15");
    $v->check("username","required|alphanum|unique:users.username|min:4|max:20");
    $v->check("email","required|email|unique:users.email|min:4|max:100");
    
    if(!$v->isValid()){
    	print_r($v->getStatus());
    }
