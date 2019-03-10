<?php

require_once 'vendor/autoload.php';
$config = require_once('config.php');




function request($url, $postdata = null, $cookiefile = __DIR__.'/tmp/cookie.txt'){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'); 
	//Эти функции работают  в паре. Эта пара неизменный компаньен всех парсеров
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile); //Берем куки в файл
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile); //отдаем куки браузеру
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	if($postdata){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	}
	$html = curl_exec($ch); //нельзя сразу ретурнить т.к. надо закрыть сurl
	curl_close($ch);
	return $html;
}

//------------------------------------------------------
//--[Main code]-----------------------------------------
//------------------------------------------------------





file_put_contents(__DIR__.'/tmp/cookie.txt','');

//$html = request('https://www.reddit.com/login');

$post = [
		'csrf_token'=>'41a7fa6165157a78c6f3fb736a61b24f49f0d34a',
		'opt'=>'login',
		'dest'=>'https://www.reddit.com',
		'username'=>$config['user'],
		'password'=>$config['password'],
];

$html = request('https://www.reddit.com/login', $post);

echo($html);