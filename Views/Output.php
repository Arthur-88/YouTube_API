<!DOCTYPE html>
<html lang="en">
	<?php	if (!isset($search)): $search=$maxResults=null ?>
	<?php endif ?>
<head>
	<title><?= (!($search)) ? 'Поиск видео по запросу' : $search ?></title>
	<meta charset="utf-8" />
	<link href="/Views/Style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<form method="get" action="/">
		<input type="search" value="<?=$search?>" name="search">
		<input type="submit" name="submit" value="Искать">
	<?php if(isset($video)): ?>
		<button type="submit" name="sort" value="viewCount">
			Сортировать по просмотрам
		</button>
	<?php endif ?>
	<p>Сколько результатов поиска вывести?
		<input type="text" value="<?=$maxResults?>" name="maxResults">
	</p>
	</form>
	<?php if(!$search): ?>
		<p><b>Введите слово для поиска</b></p>
	<?php endif ?>
	<?php if(isset($video)):?>
		<?php if(!$video):?>
			<p><b>Результаты поиска по запросу <i><?=$search?></i> отсутствуют</b></p>
		<?php endif ?>
		<div id="myAccordion" class="nl-accordion">		
		<ul>
		<?php if(!isset($video[0]["viewCount"])):?>
			<?php foreach($video as $key => $value): ?>
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
		<?php else:	?>
			<?php foreach($video as $key => $value): ?>
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
					<p style="margin: 0;">
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
	<?php endif ?>
</body>
</html>