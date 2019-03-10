<?php
/**
* https://www.youtube.com/watch?v=V6hmvgLo0Cc&list=PLD-piGJ3Dtl0eKhP4gu-B_xypyPvgDLyM&index=6
*/
namespace App\Controllers;

class Parser {
	//private $url;
	private $ch;
	private $cookiefile = __Dir__.'/../../tmp/cookie.txt';
	public  $print = false;
	
	
	public function __construct(){		
		$this->ch = curl_init();		
	}
	
	public function set($name, $value){
		curl_setopt($this->ch, $name, $value);
		return $this;
	}
	
	public function exec($url, $postdata = null){
		
		
		curl_setopt($this->ch, CURLOPT_URL, $url);
		curl_setopt($this->ch, CURLOPT_HEADER, true); //получение заголовков
		curl_setopt($this->ch, CURLOPT_NOBODY, false); // получение только заголовков без тела
		curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookiefile);
      	curl_setopt($this->ch, CURLOPT_COOKIEJAR,  $this->cookiefile);
      	curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true); //Следует за перенаправлениями
      	curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);//отключают проверки ssl
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);//отключают проверки ssl
		curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36'); 
		if($postdata) { curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postdata);}
			
		if(!$this->print){	
			//настройка для возвращения результата в строку
			 curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);}
		
		if( ! $result = curl_exec($this->ch)) 
    	{ 
       		 trigger_error(curl_error($ch)); 
   		} 
		//return curl_exec($this->ch);
		return $result;
	}
	
	public function __destructor(){
		curl_close($this->ch);
	}
	
	
	
}





	   		