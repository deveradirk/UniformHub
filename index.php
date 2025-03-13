<?php
    define("PUBLIC_DIR", join("/", [realpath(__DIR__),"public"]));
    spl_autoload_register(function($className){
	include PUBLIC_DIR . "/$className.php";
    });
    phpinfo();
