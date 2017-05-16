<?php

namespace Controllers;

use YouTube_API\videoList;
use Views\Output;

class YouTubeController
{
	public function searchVideo()
    {
		$data = self::paramsDefinition();
		extract($data,EXTR_REFS);
		
		if(isset($search) && $search) $video = (new videoList)-> getVideoData(compact("search","maxResults","sort"));
		
		return \View::render('Output',compact("video","search","maxResults"));
	}
	
	public function paramsDefinition()
	{
		if ((!isset($_REQUEST['submit']) && !isset($_REQUEST['sort'])) 
			|| (!isset($_REQUEST['search'])) || !($_REQUEST['search'])) return ([]);
		
		$search = $_REQUEST['search'];
		
		if (isset($_REQUEST['sort'])) $sort = $_REQUEST['sort'];
		else $sort = null;
			
		$maxResults = $_REQUEST['maxResults'];
		if(!$maxResults||$maxResults > 20)	$maxResults = 20;
		
		return compact("search","maxResults","sort");	
	}
}