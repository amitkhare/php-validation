<?php
	use AmitKhare\ValidBit\ValidBit as ValidBit; // use namespace.

	require(__DIR__."/validbit.php"); // only need to include if not installed via composer.

	$v = new ValidBit("localhost","slimtest","password","slimtest"); // instantiate ValidBit;
	//$v = new ValidBit(); // instantiate ValidBit;

	$v->setSource($_GET); // set data source array like = test.php?username=amit&email=amit@khare.co.in&mobile=910000000000

	$v->check("username","required|alphanum|unique:users.username|min:4|max:10");
	$v->check("email","required|email|unique:users.email|min:4|max:10");
	if($v->isValid()){
		echo "PASS";
	} else {
	    print_r($v->getStatus());
	}