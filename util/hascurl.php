<?php

function isCurl(){
    return function_exists('curl_version');
}

if (isCurl()) {
echo "You have curl";
} else {
echo "You dont have curl";
}
?>