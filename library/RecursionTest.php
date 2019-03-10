<?php

$text = "24.5 кв.м.";
$text = preg_replace('~[^.0-9]+~', "", $text);
function removeDotintheend($text){
    $last_symbol = substr($text, strlen($text)-1, 1);
    if($last_symbol == "."){
        $text = substr($text, 0, strlen($text)-1);
        $text = removeDotintheend($text);
}

    return $text;

}
$res = removeDotintheend($text);
echo $res;
//24.5