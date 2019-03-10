<?php

require_once __DIR__.'/../vendor/autoload.php';
header("Content-Type: text/html; charset=utf-8");
use \App\Controllers\Parser;

$today = date('d.m.Y');
$yesterday = date('d.m.Y', strtotime("yesterday"));

$publishDateTo = $yesterday;
$publishDateFrom = $today;
$domen = 'http://zakupki.gov.ru';
$pageNumber=1;

$publ_site = 'http://zakupki.gov.ru/epz/order/extendedsearch/results.html?searchString=&morphology=on&sortDirection=false&recordsPerPage=_20&showLotsInfoHidden=false&fz44=on&fz223=on&ppRf615=on&fz94=on&selectedSubjects=&af=true&ca=true&pc=true&pa=true&priceFromGeneral=&priceToGeneral=&priceFromGWS=&priceToGWS=&priceFromUnitGWS=&priceToUnitGWS=&currencyIdGeneral=-1&publishDateFrom='.$publishDateFrom.'&publishDateTo='.$publishDateTo.'&regions=&regionDeleted=false&sortBy=PUBLISH_DATE&openMode=USE_DEFAULT_PARAMS&pageNumber=';





$curl_parser = new Parser();



function getcontent(&$parser, $url, $page, $start, $end){
	global $domen;
	static $links_bunch = array();
	
	//Останавливаем функцию если счетчик сработал.
	if($start>=$end) return 0;
	
		
		
			
		$full_url = $url.$page;

		$data = $parser->exec($full_url);
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
$links = getcontent($curl_parser, $publ_site, $pageNumber, 0,2); //рекурсия
xprint($links);



//require_once('parse_content.php');
require_once('zakupki.gov.ru_next.php');
