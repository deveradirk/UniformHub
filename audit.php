<?php
include "database/connect.php";

$action = [
    "GET" => function() use(&$dbconn){
	$user_id = $_GET["user_id"];
	if(empty($user_id)){
	    echo json_encode([
		"message" => "Invalid request",
		"code" => 400
	    ]);
	    exit();
	}
	if(isset($_GET["fetch_all"])){
	    $stmt = $dbconn->query("SELECT * FROM audit_logs");
	    $row = $stmt->fetchAll(PDO::FETCH_NAMED);
	    if($row === false)
		$row = [];
	    echo json_encode([
		"message" => "Data fetched successfully.",
		"code" => 200,
		"data" => $row
	    ]);
	    exit();
	}
	var_dump(1);
	$stmt = $dbconn->prepare("SELECT * FROM audit_logs WHERE fk_user_id = :user_id");
	$stmt->bindValue(":user_id",$user_id);
	$stmt->execute();
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	echo json_encode([
	    "message" => "Data fetched successfully.",
	    "code" => 200,
	    "data" => $row
	]);
	exit();
    },
    "POST" => function()use(&$dbconn){
	$colnames = ["user_id", "action"];
	$post = array_map(function($elem){
	    if(!isset($_POST[$elem])){
		echo(
		    json_encode([
			"message" => "Incomplete Form Data",
			"code" => 400
		    ])
		);
		exit();
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
	    echo(json_encode([
		"message" => "Unsucessful to create an audit log",
		"code" => 200
	    ]));
	    exit();
	}
	echo(json_encode([
	    "message" => "Audit log created.",
	    "code" => 200
	]));
	exit();

    }
];

$action = $action[$_SERVER["REQUEST_METHOD"]];
if(!is_callable($action)){
    echo json_encode([
	"message" => "Invalid Method",
	"code" => 405,
    ]);
    exit();
}

$action();
