<?php
    include "database/connect.php";
    include "util/functions.php";
$action = [
    "GET" => function()use(&$dbconn) {
	$stmt = $dbconn->query("SELECT DISTINCT category, name, size, department, uniforms.image_url, COUNT(*) as available FROM stocks, uniforms WHERE stocks.fk_uniform_id = uniforms.id AND stocks.sold_to IS NULL");
	$available_uniforms = $stmt->fetch(PDO::FETCH_NAMED);
	if(empty($available_uniforms))
	    $available_uniforms = array();
	echo json_encode(array(
	    "message" => "Fetched Successfully.",
	    "code" => 200,
	    "rows" => $available_uniforms
	));
    },
    "POST" => function()use(&$dbconn){
	$dbconn->beginTransaction();
	$stmt = $dbconn->prepare("INSERT INTO uniforms(category, name, size, department, image_url) VALUES(:category, :name, :size, :department, :image_url)");
	$params = ["category", "name", "size", "department"];
	$params_missing = check_params_missing($params, $_REQUEST);
	if(!empty($params_missing)){
	    $params_missing = implode(", ", $params_missing);
	    die_json(array(
		"message" => "Missing params: $params_missing",
		"code" => 400
	    ));
	}
	$params["image_url"] = "/uniform/" . $params["department"] . "_" . $params["name"];
	foreach($params as $param_name)
	    $stmt->bindValue($param_name, $_REQUEST[$param_name]);
	$isSuccesful = $stmt->execute();
	if($isSuccesful){
	    $lastId = $dbconn->lastInsertId();
	    if($lastId === false){
		die_json(["message" => "failed to create a stocks record.", "code" => 500]);
	    }
	    var_dump($lastId);
	    $stmt = $dbconn->prepare("INSERT INTO stocks(fk_uniform_id) VALUES(:fk_uniform_id)");
	    $stmt->bindValue(":fk_uniform_id", $lastId, PDO::PARAM_INT);
	    $isSuccesful = $stmt->execute();
	    if(!$isSuccesful){
		$dbconn->rollBack();
		die_json(array(
		    "message" => "Unable to add uniform.",
		    "code" => 500
		));
	    }
	    $dbconn->commit();
	    return die_json(array(
		"message" => "Uniform successfully added.",
		"code" => 200
	    ));
	}


	$dbconn->rollBack();
	die_json(array(
	    "message" => "Unable to add uniform.",
	    "code" => 500
	));
	


    },
    "PUT" => function()use(&$dbconn){
	$params = ["user_id", "department", "size", "name"];
	$params_missing = check_params_missing($params, $_REQUEST);
	if(!empty($params_missing)){
	    $message = "Missing params: " . implode(", ", $params_missing);
	    die_json(array(
		"message" => $message,
		"code" => 400
	    ));
	}
	$stmt = $dbconn->prepare("SELECT * FROM users WHERE user_id = :user_id");
	$stmt->bindValue(":user_id", $_REQUEST["user_id"]);
	$isSucessful = $stmt->execute();
	if(!$isSucessful)
	    die_json(array(
		"message" => "Something went wrong.",
		"code" => 500
	    ));
	$user_row = $stmt->fetch(PDO::FETCH_ASSOC);
	if(empty($user_row))
	    die_json(array(
		"message" => "User does not exist",
		"code" => 200
	    ));

	$stmt = $dbconn->prepare("SELECT * FROM uniforms,stocks WHERE sold_to IS NULL AND uniforms.id = stocks.fk_uniform_id AND uniforms.department= :department AND uniforms.size= :size AND uniforms.name= :name");
	$stmt->bindValue("department", $_REQUEST["department"]);
	$stmt->bindValue("size", $_REQUEST["size"]);
	$stmt->bindValue("name", $_REQUEST["name"]);
	$stmt->execute();
	$available_row = $stmt->fetch(PDO::FETCH_ASSOC);

	if(empty($available_row))
	    die_json(array(
		"message" => "No more available stocks"
	    ));

	$stmt = $dbconn->prepare("UPDATE stocks SET sold_to = :fkUser_id WHERE id = :id");
	$stmt->bindValue(":fkUser_id", $user_row["id"], PDO::PARAM_INT);
	$stmt->bindValue(":id", $available_row["id"], PDO::PARAM_INT);
	$isSucessful = $stmt->execute();
	if(!$isSucessful)
	    die_json(array(
		"message" => "Couldn't update stocks.",
		"code" => 500
	    ));

	return die_json(array(
	    "message" => "Stocks updated.",
	    "code" => 200
	));

    }
];


$action = $action[$_SERVER["REQUEST_METHOD"]];
if(!is_callable($action))
    die(json_encode(array(
	"message" => "Method not supported.",
	"code" => 403
    )));
$action();
