<?php
class View
{
    public static function render(array $data)
    {
		extract($data,EXTR_REFS);
        ob_start();
		require './Views/Output.php';
	    $html = ob_get_clean();
        return $html;
    }
}

// ob_start - включает буферизацию вывода. Если буферизация вывода активна, вывод скрипта не высылается (кроме заголовков), а сохраняется во внутреннем буфере
// ob_get_clean() - Возвращает содержимое буфера вывода и заканчивает буферизацию вывода. Если буферизация вывода не активирована, то функция вернет FALSE
