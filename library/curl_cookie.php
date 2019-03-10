<?php

require_once 'vendor/autoload.php';


$cookiefile = __DIR__.'/tmp/cookie.txt';

$ch = curl_init('http://parserobuch.loc/cookie_test.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true); //получение заголовков

//грубый метод для имитации пользователя
//curl_setopt($ch, CURLOPT_COOKIE, 'curl_normal_cookie=1; curl_session_cookie=1');

//Эти функции работают  в паре. Эта пара неизменный компаньен всех парсеров
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile); //Берем куки в файл
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile); //отдаем куки браузеру

curl_setopt($ch, CURLOPT_COOKIESESSION,true); //если тру передается только нормальная кука которая с временем истечения а сессионная кука не передается.

$html = curl_exec( $ch );
curl_close($ch);


xprint($html);