<?php

include "database/connect.php";

if($_SERVER["REQUEST_METHOD"] != "POST")
    die(
	json_encode([
	    "message" => "Invalid Method",
	    "code" => 405
	])
    );
$colnames = [
    "user_id",
    "fullname",
    "username",
    "password",
    "role"
];
$post = array_map(function($elem){
    if(is_null($_POST[$elem]))
	die(
	    json_encode([
		"message" => "Incomplete Form Data",
		"code" => 400
	    ])
	);
    return $_POST[$elem];
},
    $colnames
);
$row = [];
for($i = 0, $len = count($colnames); $i < $len; $i++){
    $row[$colnames[$i]] = $post[$i];
}

$stmt = $dbconn->prepare("SELECT username FROM users WHERE username = :username");
$stmt->bindValue(":username", $row["username"], PDO::PARAM_STR);
$stmt->execute();
$check_row = $stmt->fetch(PDO::FETCH_ASSOC);
if(!empty($check_row)){
    die(
	json_encode([
	"message" => "User already exist.",
	"code" => 200
	])
    );
}

$stmt = $dbconn->prepare("INSERT INTO users(user_id, fullname,username,password,role) VALUES(:user_id,:fullname,:username,:password,:role)");
$row["password"] = password_hash($row["password"], PASSWORD_ARGON2ID);
for($i = 0, $len = count($colnames);$i < $len;$i++)
    $stmt->bindValue($colnames[$i], $row[$colnames[$i]], PDO::PARAM_STR);
try {
    $isSuccessful = $stmt->execute();
}catch(PDOException $e){
    echo json_encode(array(
	"message" => "Unable to create user, Malformed request.",
	"code" => 400
    ));
    die();
}

if(!$isSuccessful)
    die(
	json_encode([
	"message" => "Failed to create user",
	"code" => 200
	])
    );
else 
    die(
	json_encode([
	"message" => "Successfully created user.",
	"code" => 200
	])
    );
