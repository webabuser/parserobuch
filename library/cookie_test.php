<?php


	$cook = false;


	if(isset( $_COOKIE['curl_session_cookie'])){
		$cook = true;
		
		echo "Сессионная кука есть\r\n";
	}
	if(isset( $_COOKIE['curl_normal_cookie'])){
		$cook = true;
		
		echo "Нормальная кука есть\r\n";
	}



setcookie('curl_session_cookie', 1);
setcookie('curl_normal_cookie', 1, microtime(true)+10000);




	if($cook){
		echo 'я тебя знаю!';
	}else{
		echo 'Вы здесь новенький';
	}


