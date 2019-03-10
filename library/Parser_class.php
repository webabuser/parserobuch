<?php
/**
* https://www.youtube.com/watch?v=V6hmvgLo0Cc&list=PLD-piGJ3Dtl0eKhP4gu-B_xypyPvgDLyM&index=6
*/
header("Content-Type: text/html; charset=utf-8");
$begintime = time();
set_time_limit(0);
error_reporting(E_ERROR);

class Parser {
	private $url;
	private $ch;
	
	public function __construct($print = false){
		
		$this->ch = curl_init();
		if(!$print){
			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);	
		}

		
	}
	
	public function set($name, $value){
		curl_setopt($this->ch, $name, $value);
		return $this;
	}
	
	public function exec($url){
		curl_setopt($this->ch, CURLOPT_URL, $url);
		return curl_exec($this->ch);
		
	}
	
	public function __destructor(){
		curl_close($this->ch);
	}
	
	
	
}



function parsing($url, $start, $end){
	if($start<$end){
		
	
		$file = file_get_contents($url);
		$doc = phpQuery::newDocument($file);
		
		foreach($doc->find('.articles-container .post-excerpt') as $article ){
			$article = pq($article);
			$img = $article->find('.img-cont img')->attr('src');
			$text = $article->find('.pd-cont')->html();
			
			echo "<img src='$img'>";
			echo $text;
			echo '<hr>';
		}
		$next = $doc->find('.pages-nav .current')->next()->attr('href');
		
		if(!empty($next)){
			$start++;
			
			
			parsing($next, $start, $end);
			
		}
	
	}
	
	
}


/*/Слудующую часть можно поместеить в другой файл с подключением парсера
require_once __DIR__ . '/Parser.php';


$url_auth = 'http://.....login.php';
$url = 'http:// the page after login';
$auth_data = [
	'login' =>'admin',
	'password' => '123456',
	'remember_me' => 'on'
];

$parser = new Parser;
$parser->set(CURLOPT_POST, true)
	   ->set(CURLOPT_POSTFIELDS, http_build_query($auth_data))
	   ->set(CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt')
	   ->set(CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
	   		
$data = $parser->exec($url_auth);
$data = $parser->exec($url);

var_dump($data);
*/

$url = 'https://www.kolesa.ru/news';
$start = 0;
$end = 2;
//parsing($url, $start, $end);

$parser = new Parser(true);

$parser ->set(CURLOPT_FOLLOWLOCATION, true)
		//->set(CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt')
	 	//->set(CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt')
	   	->set(CURLOPT_HEADER, true);
	
$data = $parser->exec($url);

//$file = file_get_contents($url); норм работает
echo $file;



	   		