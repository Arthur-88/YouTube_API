<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Поиск видео по запросу</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<form method="post">
		<?php if(!isset($search)): $search=null; $maxResults=null; ?>
		<?php endif ?>
			<input type="search" value="<?=$search?>" name="search">
			<input type="submit" name="submit" value="Искать" formaction="/">
			<input type="submit" name="sort" value="Сортировать по просмотрам" formaction="/YT/VIDEOlist/viewCount">
		<p>Сколько результатов поиска вывести?
			<input type="text" value="<?=$maxResults?>" name="maxResults">
		</p>
	</form>
</body>
</html>