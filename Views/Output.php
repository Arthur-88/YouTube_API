<!DOCTYPE html>
<html lang="en">
<head>
	<title><?= $search ?></title>
	<meta charset="utf-8" />
	<link href="/Views/Style.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<?php 
		echo
		'<form method="post">
			<input type="text" value="'.$search.'" name="search">
			<input type="submit" name="submit" value="Искать" formaction="/YT/VIDEOlist">
			<input type="submit" name="sort" value="Сортировать по просмотрам" formaction="/YT/VIDEOlist/viewCount">
		<p>Сколько результатов поиска вывести?
			<input type="text" value="'.$maxResults.'" name="maxResults">
		</p>
		</form>';
	?>
	<div id="myAccordion" class="nl-accordion">		
		<ul>
		<?php	// Выводится список видео
			$i=1;
			// При отсутствии значения "viewCount" в массиве $VIDEO выводится список видео без доп.информации
			if(!isset($VIDEO[0]["viewCount"]))
				foreach($VIDEO as $value)
				{	
					echo 
						'<li>
							<input type="radio" id="nl-radio-'.$i.'" name="nl-radio" class="nl-radio" />
							<label class="nl-label" for="nl-radio-'.$i.'">'.$i.'. '.$value["title"].'</label>
							<div class="nl-content">
								<center>
								<iframe 
									width="'.$value["width"] .'" 
									height="'.$value["height"].'" 
									src="http://www.youtube.com/embed/'.$value["id"].'" 
									frameborder="0" 
									allowfullscreen>
								</iframe>
								<p style="
									margin: 0;">
								<br><b>Опубликовано: </b>'.$value["date"].'
								<br><b>Время: </b>'.$value["time"].'
								</p>
								</center>
							</div>
						</li>';
					$i++;
				}
			// При наличии значения "viewCount" в массиве $VIDEO выводится список видео с доп.информацией
			else
				foreach($VIDEO as $value)
				{	
					echo
						'<li>
							<input type="radio" id="nl-radio-'.$i.'" name="nl-radio" class="nl-radio" />
							<label class="nl-label" for="nl-radio-'.$i.'">'.$i.'. '.$value["title"].'</label>
							<div class="nl-content">
								<center>
								<iframe 
									width="'.$value["width"] .'" 
									height="270" 
									src="http://www.youtube.com/embed/'.$value["id"].'" 
									frameborder="0" 
									allowfullscreen>
								</iframe>
								<p style="
									margin: 0;">
									<b>Автор: </b>'.$value["author"].'
									<br><b>Опубликовано: </b>'.$value["date"].'
									<br><b>Время: </b>'.$value["time"].'
									<br><b>Просмотров: </b>'.$value["viewCount"].'
								</p>
							</center>
							</div>
						</li>';
					$i++;
				} 
		?>
		</ul>
	</div>
</body>
</html>