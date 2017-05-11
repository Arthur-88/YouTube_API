<?php

namespace YouTube_API;

use YouTube_API\VIDEOlist;

class VIDEOdata
{
	public function getVIDEOdata($search,$maxResults,$sort)
	{
		if($maxResults > 20)	// Количество результатов поиска ограничено 20
			$maxResults = 20;
		
		// Возвращается список видео соответствующих запросу, отсортированный по дате публикации
		$VIDEOS = new VIDEOlist;
		$VIDEO = $VIDEOS -> getVIDEOlist($search,$maxResults);
		
		if(isset($sort))	// При необходимости сортировки по другому параметру
			$VIDEO = $VIDEOS -> getVIDEOproperties($sort);	// Полученный список видео сортируется по полю $sort
				
		$VIDEOdata = compact("VIDEO","search","maxResults");	// Данные для вывода на экран объединяются в массив
		return $VIDEOdata;
	}
}
