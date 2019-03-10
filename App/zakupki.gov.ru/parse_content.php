<?php

use \Curl\MultiCurl;


$cookiefile = __Dir__.'/../tmp/cookie.txt';
// Requests in parallel with callback functions.
$multi_curl = new MultiCurl();
$multi_curl->setOpt(CURLOPT_RETURNTRANSFER,true);
$multi_curl->setOpt(CURLOPT_HEADER, true);
$multi_curl->setOpt(CURLOPT_HEADER, true);
$multi_curl->setOpt(CURLOPT_COOKIEFILE, $cookiefile);
$multi_curl->setOpt(CURLOPT_COOKIEJAR,  $cookiefile);
$multi_curl->setOpt(CURLOPT_FOLLOWLOCATION, true); //Следует за перенаправлениями
$multi_curl->setOpt(CURLOPT_SSL_VERIFYHOST, false);//отключают проверки ssl
$multi_curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);//отключают проверки ssl
$multi_curl->setOpt(CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'); 











$multi_curl->success(function($instance) {
    xprint( 'call to "' . $instance->url . '" was successful.' . "\n".
    'response'.$instance->response);
});
$multi_curl->error(function($instance) {
    echo 'call to "' . $instance->url . '" was unsuccessful.' . "\n";
    echo 'error code: ' . $instance->errorCode . "\n";
    echo 'error message: ' . $instance->errorMessage . "\n<br><br>";
});
$multi_curl->complete(function($instance) {
    echo 'call completed' . "\n";
});


foreach($links as $arr_links){
	foreach($arr_links as $link){
			$multi_curl->addGet($link, array());
	}

}



$multi_curl->start(); // Blocks until all items in the queue have been processed.


