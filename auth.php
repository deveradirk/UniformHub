<?php
$request_headers = getallheaders();
$user_access_token = $request_headers["Bearer"];
$user_refresh_token = $_REQUEST["refresh_token"];
if(is_null($user_access_token) || is_null($user_refresh_token)){
    echo json_encode([
	"message" => "Unauthorized.",
	"status" => 401
    ]);
    exit();
}
include "jwt.php";

$jwt = new JWT(API_KEY);

$user_access_token = $jwt->decode($user_access_token);
if($user_access_token === null || $user_access_token === false){
    echo json_encode([
	"message" => "Invalid Bearer Token.",
	"message" => 401
    ]);
    exit();
}
$access_header = $user_access_token[0];
$access_payload = $user_access_token[1];
$now = time();
global $access_token;
global $refresh_token;
if($access_payload['exp'] < $now){
    $user_refresh_token = $jwt->decode($user_refresh_token);
    $refresh_header = $user_refresh_token[0];
    $refresh_payload = $user_refresh_token[1];
    if($user_refresh_token === null || $user_refresh_token === false){
	echo json_encode([
	    "message" => "Access Token Expired, Invalid Refresh Token.",
	    "message" => 401
	]);
	exit();
    }
    if($refresh_payload['exp'] < $now){
	echo json_encode([
	    "message" => "Token Expired.",
	    "message" => 401
	]);
	exit();
    }
    else{
	global $access_token;
	$access_token = $jwt->encode(json_encode([]), 60 * 10);
	$refresh_iat = (int)$refresh_payload['iat'];
	$refresh_exp = (int)$refresh_payload['exp'];
	$duration = $refresh_exp - $refresh_iat;
	if(($refresh_exp - $now) <= (3600 * 168)){
	    global $refresh_token;
	    $refresh_token = jwt->encode(json_encode([]), 60 * 60 * 24 * 30);
	}
    }
	
}
else{
    $user_refresh_token = $jwt->decode($refresh_token);
    $refresh_header = $user_refresh_token[0];
    $refresh_payload = $user_refresh_token[1];
    if($refresh_payload['exp'] > $now){
	global $access_token;
	$access_token = $jwt->encode(json_encode([]), 60 * 10);
    }
}

