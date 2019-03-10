<?php

require_once __DIR__.'/../../vendor/autoload.php';
header("Content-Type: text/html; charset=utf-8");
use \Curl\Curl;



$cookiefile = __Dir__.'/../../tmp/cookie.txt';
$today = date('d.m.Y');
$yesterday = date('d.m.Y', strtotime("yesterday"));

$publishDateFrom = $yesterday;
$publishDateTo = $today;



$domen = 'http://zakupki.gov.ru';
$pageNumber = 1;
$url = $domen.'/epz/order/extendedsearch/results.html';
$urlquery = [
	'searchString'=>'',
	'morphology'=>'on',
	'sortDirection'=>'false',
	'recordsPerPage'=>'_5',
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
	'pageNumber'=>'',
];



$curl = new Curl();


$curl->setHeader('X-Requested-With', 'XMLHttpRequest');
$curl->setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36');
$curl->setReferrer('https://www.google.com');
$curl->setOpt(CURLOPT_HEADER, true);
$curl->setOpt(CURLOPT_NOBODY, false);
$curl->setOpt(CURLOPT_FOLLOWLOCATION, true);
$curl->setOpt(CURLOPT_SSL_VERIFYHOST,  false);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER,  false);
$curl->setCookieFile($cookiefile);
$curl->setCookieJar($cookiefile);




//Рекурсивная функция

function getcontent(&$curlobj, $page, $iterator){
	
	global $domen, $url, $urlquery;
	// Меняем страницу в массиве данных урла
	$urlquery['pageNumber'] = (int)$page;
	
	
	static $links_bunch = array();

	//Останавливаем функцию если счетчик итераций сработал.
	static $start = 0;
	if($start >= $iterator) return 'Сработал счетчик';
	
		

		$data = $curlobj->get($url, $urlquery);
		if	($curlobj->isError()) return 'Сервер не дал соединения';



		phpQuery::newDocument($data);
		//выбираем все ссылки на тендеры
		$tenders = pq('td.descriptTenderTd > dl > dt > a');

		//xd(pq($tenders));

		foreach($tenders as $link){
			$a= pq($link);
			//xprint ($a->attr('href'));
			
				//блок кода добавляет в ссылки название домена т.к. некоторые ссылки не полные
				$href = $a->attr('href');
				if(strripos($href, $domen) === false){
					$href = $domen.$href;
				};
			// Создаем массив из ссылок который пойдет в результат
			//Если хотим получить разбитый массив то раскоменнтить верхнюю строчку и закоментить нижнюю в следующем блоке тоже
			//$links[] = $href;//Записалось 20 линков в массив.
			array_push($links_bunch, $href); //$links_bunch[] = $href; то же самое
		}



		// Достаем breadcrumps следующую сылку.
		$next_page = pq('.page .page__link_active')
						//->dump()
						->parent()
						->next()
						->children('a.page__link')
						->attr('data-pagenumber');	
		//xprint($next_page);
		
		
		phpQuery::unloadDocuments();
		

		// Если есть следующая ссылка в бреадкрумбс то делаем по ней итерацию
		if(!empty($next_page)){
			
			$start++; //увеличиваем счетчик итерации
			//Если хотим получить разбитый массив то раскоменнтить вверку тоже провести данную операцию
			//$links_bunch[] = $links;
			
			// делаем новую итерацию где подставляем следующую страницу
			getcontent($curlobj, $next_page, $iterator); 
		}
	
		
		return $links_bunch;
			
}





$links = array();
$links = getcontent($curl, $pageNumber,1); //Рекурсивная функция.
xprint($links);
 
// Manual clean up.
//$curl->close();

//Если функция getcontent() сработала и возвратила массив, а не int 0 то идем дальше
if(is_array($links)) {
	require_once('parse_content.php');


	//require_once('zakupki.gov.ru_next.php');
}