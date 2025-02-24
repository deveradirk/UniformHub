<?php
include "database/connect.php";

$METHOD = $_SERVER["REQUEST_METHOD"];
if($METHOD === "POST"){
    $stmt = $dbconn->prepare("SELECT username,password FROM users WHERE username = ?");
    $usr = $_POST["username"];
    $password = $_POST["password"];
    if(isset($usr) && isset($password)){
	$stmt->bindValue(1, $usr, PDO::PARAM_INT);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if($usr == $row['username']){
	    $db_password = $row["password"];
	    if(password_verify($password,$db_password)){
		echo json_encode([
		    "message" => "Correct Password",
		   "code" => 200
		]);
	    }
	    else{
		echo json_encode([
		    "message" => "Wrong Password",
		    "code" => 200
		]);
	    }
	    die();
	}
	else{
	    echo json_encode([
		"message" => "User does not exist.",
		"code" => 200
	    ]);
	    die();
	}
    }
    else{
	http_response_code(400);
	echo json_encode([	    
	    "message" => "incomplete argument",
	    "code" => "400"
	]);
	die();
    }
}
else{
    http_response_code(405);
    echo json_encode([
	"message" => "Invalid Method",
	"code" => 405
    ]);
    die();
}
