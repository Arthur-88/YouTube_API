<!DOCTYPE html>
<html lang="en">
<head>
	<title><?= $search ?></title>
	<meta charset="utf-8" />
	<link href="/Views/Style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<form method="post">
			<input type="search" value="<?=$search?>" name="search">
			<input type="submit" name="submit" value="Искать" formaction="/YT/VIDEOlist">
			<input type="submit" name="sort" value="Сортировать по просмотрам" formaction="/YT/VIDEOlist/viewCount">
		<p>Сколько результатов поиска вывести?
			<input type="text" value="<?=$maxResults?>" name="maxResults">
		</p>
	</form>
	<div id="myAccordion" class="nl-accordion">		
		<ul>
		<?php // Если результаты поиска отсутствуют выводится сообщение
		if(!$VIDEO):?>
			<p><b>Результаты поиска <?=$search?> отсутствуют</b></p>
		<?php endif ?>
		<?php	// Выводится список видео
			// При отсутствии значения "viewCount" в массиве $VIDEO выводится список видео без доп.информации
			if(!isset($VIDEO[0]["viewCount"])):
				foreach($VIDEO as $key => $value): ?>
					<li>
						<input type="radio" id="nl-radio-<?=$key+1?>" name="nl-radio" class="nl-radio" />
						<label class="nl-label" for="nl-radio-<?=$key+1?>"><?=$key+1?>&nbsp;<?= $value["title"]?></label>
						<div class="nl-content">
							<center>
							<iframe 
								width="<?=$value["width"]?>" 
								height="<?=$value["height"]?>" 
								src="http://www.youtube.com/embed/<?=$value["id"]?>" 
								frameborder="0" 
								allowfullscreen>
							</iframe>
							<p style="
								margin: 0;">
							<br><b>Опубликовано: </b><?=$value["date"]?>
							<br><b>Время: </b><?=$value["time"]?>
							</p>
							</center>
						</div>
					</li>
				<?php endforeach ?>
			<?php else:	// При наличии значения "viewCount" в массиве $VIDEO выводится список видео с доп.информацией
				foreach($VIDEO as $key => $value): ?>
					<li>
						<input type="radio" id="nl-radio-<?=$key+1?>" name="nl-radio" class="nl-radio" />
						<label class="nl-label" for="nl-radio-<?=$key+1?>"><?=$key+1?>&nbsp;<?= $value["title"]?></label>
						<div class="nl-content">
							<center>
							<iframe 
								width="<?=$value["width"]?>"
								height="270" 
								src="http://www.youtube.com/embed/<?=$value["id"]?>" 
								frameborder="0" 
								allowfullscreen>
							</iframe>
							<p style="
								margin: 0;">
								<b>Автор: </b><?=$value["author"]?>
								<br><b>Опубликовано: </b><?=$value["date"]?>
								<br><b>Время: </b><?=$value["time"]?>
								<br><b>Просмотров: </b><?=$value["viewCount"]?>
							</p>
							</center>
						</div>
					</li>
				<?php endforeach ?>
			<?php endif ?>
		</ul>
	</div>
</body>
</html>