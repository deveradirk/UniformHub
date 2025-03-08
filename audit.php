<?php
include "database/connect.php";

$action = [
    "GET" => function() use(&$dbconn){
	$user_id = $_GET["user_id"];
	if(!empty($user_id)){
	    http_response_code(400);
	    echo json_encode([
		"message" => "Invalid request",
		"code" => 400
	    ]);
	    die();
	}
	$stmt = $dbconn->prepare("SELECT * FROM audit_logs WHERE user_id = :user_id");
	$stmt->bindValue(":user_id",$user_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	print_r($row);
    },
    "POST" => function()use(&$dbconn){
	$colnames = ["user_id", "action"];
	$post = array_map(function($elem){
	    if(!isset($_POST[$elem])){
		http_response_code(400);
		die(
		    json_encode([
			"message" => "Incomplete Form Data",
			"code" => 400
		    ])
		);
	    }
	    return $_POST[$elem];
	},
	    $colnames
	);
	$stmt = $dbconn->prepare("INSERT INTO audit_logs(fk_user_id, action) VALUES(:user_id, :action)");
	for($i = 0, $len = count($colnames); $i < $len; $i++)
	    $stmt->bindValue(":$colnames[$i]", $post[$i]);
	$isSuccesful = $stmt->execute();
	if(!$isSuccesful){
	    die(json_encode([
		"message" => "Unsucessful to create an audit log",
		"code" => 200
	    ]));
	}
	die(json_encode([
	    "message" => "Audit log created.",
	    "code" => 200
	]));

    }
];

$action = $action[$_SERVER["REQUEST_METHOD"]];
if(!is_callable($action)){
    print(json_encode([
	"message" => "Invalid Method",
	"code" => 405,
    ]));
    die(405);
}

$action();
