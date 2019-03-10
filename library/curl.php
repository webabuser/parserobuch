<?php

require_once 'vendor/autoload.php';


//$ch = curl_init('http://httpbin.org');
$ch = curl_init('http://ya.ru');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true); //получение заголовков
curl_setopt($ch, CURLOPT_NOBODY, true); // получение только заголовков без тела
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // если страница возвращает редирект, то следовать за редиректом
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//отключают проверки ssl
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//отключают проверки ssl

$html = curl_exec( $ch );
curl_close($ch);

xprint($html);



phpQuery::newDocument($html);




//xprint(pq($html));




phpQuery::unloadDocuments();
