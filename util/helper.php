<?php
function isCurl(){
    return function_exists('curl_version');
}
function curl($url){
	if (isCurl()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	} else {
		return file_get_contents($url);
	}
}
?>