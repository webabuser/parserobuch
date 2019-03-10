<?php

require_once 'vendor/autoload.php';

$html = file_get_contents('http://pogoda.yandex.ru');

//xprint($html, '$html');



phpQuery::newDocument($html);

//xd($g['title']);
//$title = pq('title'); //как в JQuery только в место знака $ - пишется pq.
//xd($title);
//$title = pq('title')->html();
//$temperature = pq('.temp__value:5th-child(1)')->text();
//$a = pq('body > div.b-page__container > div.content > div.forecast-briefly > div > div.forecast-briefly__day.forecast-briefly__day_weekstart_0.day-anchor.i-bem > a')->text();



$a = pq('div.forecast-briefly__day')->children('a.i-bem');

xprint($a->html());


foreach($a as $li){
	//xprint(pq($li));
	//xprint(pq($li)->html());
	
	$l = pq($li);
	$l->find('.forecast-briefly__name')->remove();
	$l->prepend('<div>HHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHHH</div>');
	xprint($l->html());
	
}



phpQuery::unloadDocuments();
