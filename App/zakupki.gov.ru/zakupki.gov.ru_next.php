<?php

use \Curl\MultiCurl;


$cookiefile = __Dir__.'/../tmp/mcookie.txt';
// Requests in parallel with callback functions.




function multirequest($urls){
	global $cookiefile;

	//Создаем главный дескриптор
	$multi = curl_multi_init();
	$handles = [];
	$htmls = [];

	foreach($urls as $url){
		$ch = curl_init($url);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER,true);	
		curl_setopt( $ch, CURLOPT_HEADER, true);
		curl_setopt( $ch, CURLOPT_COOKIEFILE, $cookiefile);
		curl_setopt( $ch, CURLOPT_COOKIEJAR,  $cookiefile);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true); //Следует за перенаправлениями
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);//отключают проверки ssl
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);//отключают проверки ssl
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'); 
		
		curl_multi_add_handle($multi, $ch);
		$handles[$url] = $ch;
		
	}


		$active  = null;

       	do {
            $mrc = curl_multi_exec($multi, $active);
        } 
        while ($mrc == CURLM_CALL_MULTI_PERFORM);
    
        while ($active && $mrc == CURLM_OK) {
            // Wait for activity on any curl-connection
            if (curl_multi_select($multi) == -1) {
            	//if it returns -1? wait a bti, but go forward anyways!
                usleep(1);
            }
    
            // Continue to exec until curl is ready to
            // give us more data
            do {
                $mrc = curl_multi_exec($multi, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }
        
    foreach($handles as $channel){
    	
		$html = curl_multi_getcontent( $channel );
	
		$htmls[]= $html;
		curl_multi_remove_handle($multi, $channel);
			/*if (!curl_errno($channel)) {
			  $info = curl_getinfo($channel);
			  echo 'Прошло ', $info['total_time'], ' секунд во время запроса к ', $info['url'], "\n<br>";
			}*/
	}    
	curl_multi_close($multi);
	
	return $htmls;
}	

foreach($links as $arr_links){
	$htmls = multirequest($arr_links);
	xprint($htmls);
}
