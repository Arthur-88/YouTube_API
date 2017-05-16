<?php

$url = $_SERVER['REQUEST_URI'];

$routes = include './routes.php';

$route = searchRoute($routes, $url);
$data = parseRoute($route);

return call($data['class'], $data['method'], $data['params']);

/**
 * Ищет ссылку в массиве
 *
 * @param $routes
 * @param $url
 * @return mixed
 * @throws Exception
 */
function searchRoute($routes, $url)
{
    $data = array();
    foreach ($routes as $key => $value) 
	{
		$pattern = '/^([^?]+)(\?.*?)?(#.*)?$/';
		if (preg_match($pattern, $url, $matches)) 		
		{
			array_shift($matches);
            $data['route'] = $key;
            $data['uses'] = $value;
            $data['params'] = $matches;
			return $data;
        }
    }
    throw new Exception('Путь не найден!');
}

/**
 * Разбирает путь на части
 *
 * @param $route
 * @return array
 */
function parseRoute($route)
{
    $routeParts = explode('/', $route['uses']);

    $data = array(
        'class' => array_shift($routeParts),
        'method' => array_shift($routeParts),
        'params' => $route['params']
    );
    return $data;
}

/**
 * Вызывает метод в указанном классе с параметрами
 *
 * @param $className
 * @param $methodName
 * @param $params
 * @return mixed
 */
function call($className, $methodName, $params)
{
    return call_user_func_array(['Controllers\\' . $className, $methodName], $params);
}