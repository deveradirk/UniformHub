<?php
include "database/connect.php";

if($_SERVER["REQUEST_METHOD"] != "POST"){
    http_response_code(405);
    echo json_encode([
	"message" => "Invalid Method",
	"code" => 405
    ]);
    exit();
}
$col = ["fullname","username","password","role"];
$post = array_map(function($elem){
    if(!isset($_POST[$elem])){
	http_response_code(400);
	echo json_encode([
	    "message" => "Incomplete Form Data",
	    "code" => 400
	]);
	exit();
    }
    return $_POST[$elem];
},
    $col
);
$stmt = $dbconn->prepare("SELECT username FROM users WHERE username = ?");
$stmt->bindValue(1, $post[1], PDO::PARAM_STR);
$stmt->execute();
$check_row = $stmt->fetch(PDO::FETCH_ASSOC);
if($check_row["username"] == $post[1]){
    echo json_encode([
	"message" => "User already exist.",
	"code" => 200
    ]);
    exit();
}

$stmt = $dbconn->prepare("INSERT INTO users(fullname,username,password,role) VALUES(?,?,?,?)");
$post[2] = password_hash($post[2], PASSWORD_ARGON2ID);
for($i = 0, $len = count($col);$i < $len;$i++){
    $stmt->bindParam($i+1, $post[$i], PDO::PARAM_STR);
}
$isSuccessful = $stmt->execute();


if(!$isSuccessful){
    echo json_encode([
	"message" => "Failed to create user",
	"code" => 200
    ]);
    die();
}
else {
    echo json_encode([
	"message" => "Successfully created user.",
	"code" => 200
    ]);
    die();
}
