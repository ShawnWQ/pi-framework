<?php

/*
 * When i deal with memory exausthed i'll implement a global reseter and each test may increase
 * This way i can control more the way im programming i guess
 */

class PHP_Token_SELECT {
    public function __toString() { return 'dumb'; }
}

if(!class_exists('MongoCursorException')) {
	class MongoCursorException extends \Exception { }
}

if(!class_exists('PHP_Token_XHP_REQUIRED')) {
	class PHP_Token_XHP_REQUIRED {}
}

if(!class_exists('PHP_Token_ASYNC')) {
	class PHP_Token_ASYNC extends \PHP_Token { }
}

if(!class_exists('PHP_Token_WHERE')) {
	class PHP_Token_WHERE extends \PHP_Token {}
}

if(!class_exists('PHP_Token_ENUM')) {
	class PHP_Token_ENUM extends \PHP_Token {}
}

if(!class_exists('PHP_Token_ONUMBER')) {
	class PHP_Token_ONUMBER extends \PHP_Token {}
}

error_reporting(E_ALL);

require_once(__DIR__ . '/../vendor/autoload.php');

spl_autoload_register(function ($class_name) {
	if(file_exists($class_name . '.php')) {
		require $class_name . '.php';
		return true;
	}
    return false;
});

date_default_timezone_set('Europe/Lisbon');