<?php
include "database/connect.php";

$action = [
    "GET" => function() use(&$dbconn){
	$userID = $_GET["user_id"];
	if(isset($userID)){
	    http_response_code(400);
	    echo json_encode([
		"message" => "Invalid request",
		"code" => 400
	    ]);
	    die();
	}
	$stmt = $dbconn->prepare("SELECT * FROM audit_logs WHERE user_id = ?");
	$stmt->bindValue(1,$userID);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	print_r($row);
    },
    "POST" => function()use(&$dbconn){
	
    }
];

$action = $action[$_SERVER["REQUEST_METHOD"]];
if($action == null){
    print(json_encode([
	"message" => "Invalid Method",
	"code" => 405,
    ]));
    die(405);
}

$action();
