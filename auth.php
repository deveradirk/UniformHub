<?php
$request_headers = getallheaders();
$bearer = $request_headers["Bearer"];
if(is_null($bearer)){
    http_response_code(401);
    exit();
}
include "jwt.php";

$refresh_jwt = new JWT(API_KEY, 7 * 60 * 60 * 24); // 1 week
$access_jwt = new JWT(API_KEY, 60 * 60); // 1 hour


