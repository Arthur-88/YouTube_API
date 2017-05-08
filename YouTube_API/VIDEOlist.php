<?php

namespace YouTube_API;
use Helpers\JSON;

class VIDEOlist
{
	
	protected $VIDEOlist = array();	// Для получения доп.информации для каждого видео массив доступен во всех методах
	
/* Метод обращается к API YouTube с типом запроса $requestTYPE и параметрами запроса $PARAMS.
 * Возвращает список видео в форме массив объектов
 */	protected function YTrequest(array $PARAMS,$requestTYPE)
    {
		$url = 'https://www.googleapis.com/youtube/v3/';
		$PARAMS['key'] = include 'App_params.php';	// Ключ к API YouTube хранится в файле App_params.php
	
		$requestPARAMS = http_build_query($PARAMS);
		$HTTPrequest = $url.$requestTYPE.'&'.$requestPARAMS;
		
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $HTTPrequest);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,	FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,	FALSE);
        $result_JSON = curl_exec($curl);
        curl_close($curl);
		$result_ARRAY = JSON::fromJson($result_JSON);
		
		if (!isset($result_ARRAY -> items))	
			return false;
		
		return $result_ARRAY -> items;
	}
	
/* Метод возвращает в форме массива список видео, соответствующих запросу $search, отсортированных по дате разщемещения
 * количество элементов массива соответствует $maxResults
 */	public function getVIDEOlist($search,$maxResults)
    {
		$requestTYPE = 'search?';	// Тип запроса - поиск
		
		// Параметры запроса для поиска видео
		$searchPARAMS = [
			'part' => 'snippet',
			'q' => &$search,	// Поиск выполняется по полю $search
			'type' => 'video',	// Тип возвращаемых объектов - видео
			'maxResults' => &$maxResults,	// Количество возвращаемых объектов соответствует $maxResults 
			'order' => 'date',	// Сортировка по дате размещения видео
		];
		
	/* Вызывает запрос к API YouTube с типом $requestTYPE и параметром $searchPARAMS,
	 * результат запроса возвращаются в виде массива объектов
	 */	$this -> VIDEOlist = self::YTrequest($searchPARAMS,$requestTYPE);
		if (!isset($this -> VIDEOlist))	// Проверяется наличие результатов поиска
			return false;
	
		// Результаты поиска из массива обектов преобразуется в многомерный ассоциативный массив
		$VIDEOlistFormated = self::formatVIDEOlist($this -> VIDEOlist);
		
		return $VIDEOlistFormated;	// Многомерный ассоциативный массив с результатом поиска возвращается в контроллер
    }
	
/* Метод получает дополнительную информацию для каждого видео массива объектов $VIDEOlist,
 * возвращает многомерный ассоциативный массив, отсортированный по полю $sort
 */	public function getVIDEOproperties($sort)
    {
		$requestTYPE = 'videos?';	// Тип запроса - получение свойства видео
		$VIDEOstatistics_ARRAY = array();
		
	/* Цикл получает для каждого видео доп.информацию(количество просмотров, HTML тег),
	 * добавлет видео с доп.информацией в общий массив объектов $VIDEOstatistics_ARRAY
	 */	foreach($this -> VIDEOlist as $value)
		{
			// Параметры запроса для получения доп.информации по каждому видео
			$videoParams = [
				'id' => $value->id->videoId,	// Доп.информация получается по id видео
				'part' => 'snippet,statistics,player',
				'fields' => 'items(id,snippet(title,thumbnails,publishedAt,channelTitle),statistics/viewCount,player/embedHtml)',
			];
			
		/* Вызывает запрос к API YouTube с типом $requestTYPE и параметром $searchPARAMS,
		 * результат запроса возвращаются в виде массива объектов
		 */	$VIDEOstatistics = self::YTrequest($videoParams,$requestTYPE);
			$VIDEOstatistics_ARRAY[] = $VIDEOstatistics[0];	// Формируется общий массив видео с доп.информацией
		}
		
		// Общий массив видео с доп.информацией преобразуется в многомерный ассоциативный массив
		$VIDEOlistFormated = self::formatVIDEOlistExt($VIDEOstatistics_ARRAY);
		
		// Сортирует многомерный ассоциативный массив по полю $sort
		$VIDEOlistSorted = self::sortVIDEO($VIDEOlistFormated,$sort);
		
		return $VIDEOlistSorted;	// Отсортированный массив видео с доп.информацией возвращается в контроллер
	}

// Результаты поиска из массива обектов $VIDEOlist преобразуется в многомерный ассоциативный массив $VIDEOlistFormated
	public function formatVIDEOlist(array $VIDEOlist)
	{
		$VIDEOlistFormated = array();
		foreach($VIDEOlist as $value)
		{
			$date = explode('T', $value -> snippet -> publishedAt);
			$time = explode('.', $date[1]);
			$VIDEOlistFormated[] = array
			(   'id'            =>  $value -> id -> videoId,
				'href'          =>  'https://www.youtube.com/embed/'.$value -> id -> videoId,
				'title'         =>  $value -> snippet      -> title,
				'date'          =>  $date[0],
				'time'			=>	$time[0],
				'image'         =>  $value -> snippet      -> thumbnails   -> high -> url,
				'width'         =>  $value -> snippet      -> thumbnails   -> high -> width,
				'height'        =>  $value -> snippet      -> thumbnails   -> high -> height,
				'author'		=>	$value -> snippet      -> channelTitle,
			);
		}
		return $VIDEOlistFormated;
	}

// Массив видео с доп.информацией $VIDEOstatistics преобразуется в многомерный ассоциативный массив $VIDEOlistFormated
	public function formatVIDEOlistExt(array $VIDEOstatistics)
	{
		$VIDEOlistFormated = array();
		foreach($VIDEOstatistics as $value)
		{
			$date = explode('T', $value -> snippet -> publishedAt);
			$time = explode('.', $date[1]);
			$VIDEOlistFormated[] = array
			(   'id'            =>  $value -> id,
				'href'          =>  'https://www.youtube.com/embed/'.$value -> id,
				'title'         =>  $value -> snippet      -> title,
				'date'          =>  $date[0],
				'time'			=>	$time[0],
				'image'         =>  $value -> snippet      -> thumbnails   -> high -> url,
				'width'         =>  $value -> snippet      -> thumbnails   -> high -> width,
				'height'        =>  $value -> snippet      -> thumbnails   -> high -> height,
				'viewCount'     =>  $value -> statistics   -> viewCount,
				'VIDEOtag'      =>  $value -> player       -> embedHtml,	//HTML тег для вставки видео
				'author'		=>	$value -> snippet      -> channelTitle,
			);
		}
		return $VIDEOlistFormated;
	}

	// Сортирует многомерный ассоциативный массив видео с доп.информацией по полю $sort
	public static function sortVIDEO(array $VIDEOlistFormated, $sort)
	{
		for($i=0;$i+1<count($VIDEOlistFormated);)
		{
			if ($VIDEOlistFormated[$i][$sort] < $VIDEOlistFormated[$i+1][$sort])
			{
				$TEMPVIDEOlist = $VIDEOlistFormated[$i+1];
				$VIDEOlistFormated[$i+1] = $VIDEOlistFormated[$i];
				$VIDEOlistFormated[$i] = $TEMPVIDEOlist;
				$i = 0;
			}
			else $i++;
		}
			return $VIDEOlistFormated;
	}
}