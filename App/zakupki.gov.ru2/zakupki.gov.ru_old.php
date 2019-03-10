<?php

require_once __DIR__.'/../vendor/autoload.php';
header("Content-Type: text/html; charset=utf-8");
use \Curl\Curl;



$cookiefile = __Dir__.'/../tmp/cookie.txt';
$today = date('d.m.Y');
$yesterday = date('d.m.Y', strtotime("yesterday"));

$publishDateTo = $yesterday;
$publishDateFrom = $today;
$domen = 'http://zakupki.gov.ru';
$pageNumber=1;

$publ_site = 'http://zakupki.gov.ru/epz/order/extendedsearch/results.html?searchString=&morphology=on&sortDirection=false&recordsPerPage=_20&showLotsInfoHidden=false&fz44=on&fz223=on&ppRf615=on&fz94=on&selectedSubjects=&af=true&ca=true&pc=true&pa=true&priceFromGeneral=&priceToGeneral=&priceFromGWS=&priceToGWS=&priceFromUnitGWS=&priceToUnitGWS=&currencyIdGeneral=-1&publishDateFrom='.$publishDateFrom.'&publishDateTo='.$publishDateTo.'&regions=&regionDeleted=false&sortBy=PUBLISH_DATE&openMode=USE_DEFAULT_PARAMS&pageNumber=';





$url = 'http://zakupki.gov.ru/epz/order/extendedsearch/results.html';
$data = [
	'searchString'=>'',
	'morphology'=>'on',
	'sortDirection'=>'false',
	'recordsPerPage'=>'_20',
	'showLotsInfoHidden'=>'false',
	'fz44'=>'on',
	'fz223'=>'on',
	'ppRf615'=>'on',
	'fz94'=>'on',
	'selectedSubjects'=>'',
	'af'=>'true',
	'ca'=>'true',
	'pc'=>'true',
	'pa'=>'true',
	'priceFromGeneral'=>'',
	'priceToGeneral'=>'',
	'priceFromGWS'=>'',
	'priceToGWS'=>'',
	'priceFromUnitGWS'=>'',
	'currencyIdGeneral'=>'-1',
	'publishDateFrom'=>$publishDateFrom,
	'publishDateTo'=>$publishDateTo,
	'regions'=>'',
	'regionDeleted'=>'false',
	'sortBy'=>'PUBLISH_DATE',
	'openMode'=>'USE_DEFAULT_PARAMS',
	'pageNumber'=>$pageNumber
];



$curl = new Curl();


$curl->setHeader('X-Requested-With', 'XMLHttpRequest');
$curl->setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36');
$curl->setReferrer('https://www.example.com/url?url=https%3A%2F%2Fwww.example.com%2F');
$curl->setOpt(CURLOPT_HEADER, true);
$curl->setOpt(CURLOPT_NOBODY, false);
$curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
$curl->setOpt(CURLOPT_SSL_VERIFYHOST,  false);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER,  false);
$curl->setCookieFile($cookiefile);
$curl->setCookieJar($cookiefile);





function getcontent(&$parser, $url, $page, $start, $end){
	global $domen;
	static $links_bunch = array();
	
	//Останавливаем функцию если счетчик сработал.
	if($start>=$end) return 0;
	
		
		
			
		$full_url = $url.$page;

		$data = $parser->get($full_url);
		//xprint($data);
		phpQuery::newDocument($data);

		$tenders = pq('td.descriptTenderTd > dl > dt > a');

		//xd(pq($tenders));

		foreach($tenders as $link){
			$a= pq($link);
			//xprint ($a->attr('href'));
			//Если в ссылке нет имени домена то добавляем его туда.
			$href = $a->attr('href');
			if(strripos($href, $domen) === false){
				$href = $domen.$href;
			};
			
			$links[] = $href;//Записалось 20 линков в массив.
		}




		$next_page = pq('.page .page__link_active')
						//->dump()
						->parent()
						->next()
						->children('a.page__link')
						->attr('data-pagenumber');	
		//xprint($next_page);
		
		
		phpQuery::unloadDocuments();
		

		
		if(!empty($next_page)){
			
			$start++; //счетчик конца
			
			$links_bunch[] = $links;
			
			getcontent($parser, $url, $next_page, $start, $end);
		}
	
		
		return $links_bunch;
			
}





$links = array();
$links = getcontent($curl, $publ_site, $pageNumber, 0,2); //рекурсия
xprint($links);

// Manual clean up.
$curl->close();


//require_once('parse_content.php');
//require_once('zakupki.gov.ru_next.php');
