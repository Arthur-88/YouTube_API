<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Поиск видео по запросу</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
	<form method="post">
        <input type="text" name="search">
	    <input type="submit" name="submit" value="Искать" formaction="YT/VIDEOlist">
		<p>Сколько результатов поиска вывести?
			<input type="text" name="maxResults">
		</p>
	</form>
</body>
</html>