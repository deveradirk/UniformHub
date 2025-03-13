<?php
$ini = parse_ini_file(".env");
define("API_KEY", $ini["API_KEY"]);
class JWT{
    private string $secret_key;
    private int $expiration_duration;


    public function __construct(string $signed_key, int $expiration_duration)
    {
	$this->secret_key = $signed_key;
	$this->expiration_duration = $expiration_duration;
    }

    private function encode_base64(string $text) : string {
	return str_replace(["+","/","="],["-","_",""], base64_encode($text));
    }
    private function decode_base64(string $base64) : string {
	return base64_decode(str_replace(["-","_"],["+", "/"], $base64));
    }
    public function encode(string $payload) : string {
	$issuedAt = time();
	$jwt_header = [
	    "alg" => "HS512",
	    "typ" => "JWT",
	];
	$jwt_payload = [
	    "iat" => $issuedAt,
	    "exp" => $issuedAt+$this->expiration_duration,
	    "payload" => $payload
	];

	$encoded_header = $this->encode_base64(json_encode($jwt_header));
	$encoded_payload = $this->encode_base64(json_encode($jwt_payload));


	$signature = hash_hmac("sha512", $encoded_header.$encoded_payload, $this->secret_key, true);
	$encoded_signature = $this->encode_base64($signature);
	return join(".",[$encoded_header,$encoded_payload,$encoded_signature]);
    }
    public function decode(string $token) : null | false | array {
	$exploded_token = [$header, $payload, $signature] = explode(".", $token);
	if(in_array(null,$exploded_token))
	    return null;
	if(!hash_hmac("sha512", "$header.$payload", $this->secret_key, true) === $signature)
	    return false;
	$header = $this->decode_base64($header);
	$payload = $this->decode_base64($payload);
	return [$header, $payload];
    }

}

