<?php
try {
    $dbconn = new PDO(
	"mysql:host=localhost;dbname=UniformHub",
	"user",
	"password",
	array(
	    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
	)
    );
}
catch(PDOException $e){
    die(
	json_encode([
	    "message" => "Server cannot connect to the database.",
	    "code", 500
	])
    );
}

