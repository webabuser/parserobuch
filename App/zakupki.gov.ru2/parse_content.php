<?php

use \Curl\MultiCurl;


$cookiefile = __Dir__.'/../../tmp/mcookie.txt';
// Requests in parallel with callback functions.
$multi_curl = new MultiCurl();
$multi_curl->setOpt(CURLOPT_RETURNTRANSFER,true);
$multi_curl->setOpt(CURLOPT_HEADER, true);
$multi_curl->setOpt(CURLOPT_NOBODY, true);
$multi_curl->setOpt(CURLOPT_COOKIEFILE, $cookiefile);
$multi_curl->setOpt(CURLOPT_COOKIEJAR,  $cookiefile);
$multi_curl->setOpt(CURLOPT_FOLLOWLOCATION, true); //Следует за перенаправлениями
$multi_curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);//отключают проверки ssl
$multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);//отключают проверки ssl
$multi_curl->setOpt(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'); 
$multi_curl->setReferrer('https://www.example.com/url?url=https%3A%2F%2Fwww.example.com%2F');
$multi_curl->setHeader('X-Requested-With', 'XMLHttpRequest');










$multi_curl->success(function($instance) {
    xprint( 'call to  ' . $instance->url . ' -- was successful.' . "\n"
    		.'response'.$instance->response);
});
$multi_curl->error(function($instance) {
    xprint( 'call to  ' . $instance->url . ' -- was unsuccessful.' . "\n"
    		. 'error code: ' . $instance->errorCode . "\n"
   			. 'error message: ' . $instance->errorMessage . "\n");
});
$multi_curl->complete(function($instance) {
    echo 'call completed' . "\n";
});

/*
foreach($links as $arr_links){
	foreach($arr_links as $link){
			$multi_curl->addGet($link, array());
	}
}
/* Рекурсивно проходим по полученному массиву ссылок на тендеры и используюя колэбл функцию добавляем в мультикурл. (замена верхнего блока)
Это позволяет не привязываться к тому разбит массив в предыдущем файле или  нет.
*/

array_walk_recursive($links, function($value) use ($multi_curl) {	
	$multi_curl->addGet($value, array());
});


$multi_curl->start(); // Blocks until all items in the queue have been processed.


