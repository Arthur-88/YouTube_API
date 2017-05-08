<?php

namespace Controllers;

use YouTube_API\VIDEOlist;
use Views\Output;
use Helpers\JSON;

class YouTubeController
{
	public function searchVIDEO($sort = null)
    {
		$error = ['errorMessage' => 'Введите слово для поиска'];
        if (empty($_POST['search']))	// Проверяется ввод поискового запроса
			return JSON::toJson($error); 
		
		$search = $_POST['search'];
		$maxResults = $_POST['maxResults'];
		if($maxResults > 20)	// Количество результатов поиска ограничено 20
			$maxResults = 20;
		
	/* Список видео возвращается отсортированным по дате публикации. 
	 * Если нет необходимости сортировать список по другому параметру,
	 */	if(!$sort)
			// Возвращается список видео, соответствующих запросу
			$VIDEO = (new VIDEOlist) -> getVIDEOlist($search,$maxResults);
		else	// При необходимости сортировки
		{
			$VIDEOS = new VIDEOlist;
			$VIDEOlist = $VIDEOS -> getVIDEOlist($search,$maxResults);	// Сначала получается новый список видео
			$VIDEO = $VIDEOS -> getVIDEOproperties($sort);	// Полученный список видео сортируется по полю $sort
		}
		
		if(!$VIDEO)	// Если результаты поиска отсутствуют возвращается сообщение
			return 'Результаты поиска отсутствуют';
		
		$data = compact("search","maxResults","VIDEO");	// Данные для вывода на экран объединяются в массив
		
		// Вызывается метод включения данных для вывода на экран в HTML код, результат возвращается в роутер
		return \View::render($data);
	}
}