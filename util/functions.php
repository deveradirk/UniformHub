<?php

function check_params_exist(array $keys, array $haystack){
    for($i = 0, $len = count($keys); $i < $len; $i++)
	$keys[$i] = !is_null($haystack[$keys[$i]]);
    return $keys;
}
function check_params_missing(array $keys, array $haystack){
    $new_array = [];
    for($i = 0 ,$len = count($keys); $i < $len; $i++)
	if(is_null($haystack[$keys[$i]])){
	    array_push($new_array, $keys[$i]);
	}
    return $new_array;
}

function die_json(array $json){
    return die(json_encode($json));
}
