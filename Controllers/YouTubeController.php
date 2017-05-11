<?php

namespace Controllers;

use YouTube_API\VIDEOdata;
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
		
	// Возвращается массив данных для вывода на экран (список видео, соответствующих запросу, запрос, количество видео)
		$data = (new VIDEOlist) -> getVIDEOdata($search,$maxResults,$sort);
			
		// Вызывается метод включения в HTML код данных для вывода на экран, результат возвращается в роутер
		return \View::render($data);
	}
}