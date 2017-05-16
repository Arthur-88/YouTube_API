<?php

namespace YouTube_API;
use Helpers\JSON;

class videoList
{
	protected $videoList = array();	// Для получения доп.информации для каждого видео массив доступен во всех методах
	
//	public function getVideoData($search,$maxResults,$sort)
	public function getVideoData($data)
	{
		extract($data,EXTR_REFS);
	// Возвращается список видео соответствующих запросу, отсортированный по дате публикации
		$video = self::getVideoList($search,$maxResults);
	// При необходимости сортировки по другому параметру gолученный список видео сортируется по полю $sort
		if(isset($sort)) $video = self::getVideoProperties($sort);
		return $video;
	}
	
/* Метод обращается к API YouTube с типом запроса $requestType и параметрами запроса $params.
 * Возвращает список видео в форме массив объектов
 */	protected function ytRequest(array $params,$requestType)
    {
		$url = 'https://www.googleapis.com/youtube/v3/';
		$params['key'] = include './Configs/App_params.php';	// Ключ к API YouTube хранится в файле App_params.php
	
		$requestParams = http_build_query($params);
		$httpRequest = $url.$requestType.'&'.$requestParams;
		
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $httpRequest);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,	false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,	false);
        $result_JSON = curl_exec($curl);
        curl_close($curl);
		$result_ARRAY = JSON::fromJson($result_JSON);
		
		if (!isset($result_ARRAY-> items))	return false;
		
		return $result_ARRAY-> items;
	}
	
/* Метод возвращает в форме массива список видео, соответствующих запросу $search, отсортированных по дате разщемещения
 * количество элементов массива соответствует $maxResults
 */	public function getVideoList($search,$maxResults)
    {
		$requestType = 'search?';	// Тип запроса - поиск
		
		// Параметры запроса для поиска видео
		$searchParams = [
			'part' => 'snippet',
			'q' => &$search,	// Поиск выполняется по полю $search
			'type' => 'video',	// Тип возвращаемых объектов - видео
			'maxResults' => &$maxResults,	// Количество возвращаемых объектов соответствует $maxResults 
			'order' => 'date',	// Сортировка по дате размещения видео
		];
		
	/* Вызывает запрос к API YouTube с типом $requestType и параметром $searchParams,
	 * результат запроса возвращаются в виде массива объектов
	 */	$this-> videoList = self::ytRequest($searchParams,$requestType);
		if (!isset($this-> videoList))	// Проверяется наличие результатов поиска
			return false;
	
		// Результаты поиска из массива обектов преобразуется в многомерный ассоциативный массив
		$videoListFormated = self::formatVideoList($this-> videoList);
		
		return $videoListFormated;	// Многомерный ассоциативный массив с результатом поиска возвращается в контроллер
    }
	
/* Метод получает дополнительную информацию для каждого видео массива объектов videoList,
 * возвращает многомерный ассоциативный массив, отсортированный по полю $sort
 */	public function getVideoProperties($sort)
    {
		$requestType = 'videos?';	// Тип запроса - получение свойства видео
		$videoStatistics_ARRAY = array();
		
	/* Цикл получает для каждого видео доп.информацию(количество просмотров, HTML тег),
	 * добавлет видео с доп.информацией в общий массив объектов $videoStatistics_ARRAY
	 */	foreach($this-> videoList as $value)
		{
			// Параметры запроса для получения доп.информации по каждому видео
			$videoParams = [
				'id' => $value->id->videoId,	// Доп.информация получается по id видео
				'part' => 'snippet,statistics,player',
				'fields' => 'items(id,snippet(title,thumbnails,publishedAt,channelTitle),statistics/viewCount,player/embedHtml)',
			];
			
		/* Вызывает запрос к API YouTube с типом $requestType и параметром $searchParams,
		 * результат запроса возвращаются в виде массива объектов
		 */	$videoStatistics = self::ytRequest($videoParams,$requestType);
			$videoStatistics_ARRAY[] = $videoStatistics[0];	// Формируется общий массив видео с доп.информацией
		}
		
		// Общий массив видео с доп.информацией преобразуется в многомерный ассоциативный массив
		$videoListFormated = self::formatVideoListExt($videoStatistics_ARRAY);
		
		// Сортирует многомерный ассоциативный массив по полю $sort
		$videoListSorted = self::sortVideo($videoListFormated,$sort);
		
		return $videoListSorted;	// Отсортированный массив видео с доп.информацией возвращается в контроллер
	}

// Результаты поиска из массива обектов $videoList преобразуется в многомерный ассоциативный массив $videoListFormated
	public function formatVideoList(array $videoList)
	{
		$videoListFormated = array();
		foreach($videoList as $value)
		{
			$date = explode('T', $value -> snippet -> publishedAt);
			$time = explode('.', $date[1]);
			$videoListFormated[] = array
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
		return $videoListFormated;
	}

// Массив видео с доп.информацией $videoStatistics преобразуется в многомерный ассоциативный массив $videoListFormated
	public function formatVideoListExt(array $videoStatistics)
	{
		$videoListFormated = array();
		foreach($videoStatistics as $value)
		{
			$date = explode('T', $value -> snippet -> publishedAt);
			$time = explode('.', $date[1]);
			$videoListFormated[] = array
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
		return $videoListFormated;
	}

	// Сортирует многомерный ассоциативный массив видео с доп.информацией по полю $sort
	public static function sortVideo(array $videoListFormated, $sort)
	{
		for($i=0;$i+1<count($videoListFormated);)
		{
			if ($videoListFormated[$i][$sort] < $videoListFormated[$i+1][$sort])
			{
				$tempVideoList = $videoListFormated[$i+1];
				$videoListFormated[$i+1] = $videoListFormated[$i];
				$videoListFormated[$i] = $tempVideoList;
				$i = 0;
			}
			else $i++;
		}
			return $videoListFormated;
	}
}