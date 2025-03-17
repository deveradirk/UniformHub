<?php
include "database/connect.php";
include "util/functions.php";
$action = [
    "GET" => function ()use(&$dbconn){
	$userId = $_REQUEST["user_id"];
	if(is_null($userId))
	    die_json(["message" => "missing param `user_id`", "code" => 400]);
	$stmt = $dbconn->prepare("SELECT fullname, email, role FROM users WHERE BINARY user_id = :user_id");
	$isSuccessful = $stmt->execute(array(
	    "user_id" => $userId
	));
	if(!$isSuccessful){
	    die_json(["message" => "something went wrong.", "code" => 500]);
	}
	$row = $stmt->fetch(PDO::FETCH_NAMED);
	if($row === false)
	    die_json(["message" => "record does not exist", "code" => 404]);
	die_json($row);
    }

];


$action = $action[$_SERVER["REQUEST_METHOD"]];
if(!is_callable($action))
    die(json_encode(array(
	"message" => "Method not supported.",
	"code" => 403
    )));
$action();
