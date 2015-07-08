<?php
/**
 * @package		Joomla.Plugin
 * @subpackage	Content.loadmodule
 * @copyright	Copyright (C) 2010 - 2014 Nordmograph.com, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
class plgContentLoadsoundcloud extends JPlugin{
	public function onContentPrepare($context, &$article, &$params, $page = 0) {
		// simple performance check to determine whether bot should process further
		if (strpos($article->text, '://soundcloud.com/') === false) {
			return true;
		}
		$mode		= $this->params->def('mode','1');
		$auto_play			= $this->params->def('auto_play',0);
		$visual			= $this->params->def('visual',1);
		$show_comments		= $this->params->def('show_comments',1);
		$show_description	= $this->params->def('show_description',1);
		$show_artwork		= $this->params->def('show_artwork',1);
		$hide_related		= $this->params->def('hide_related',1);  // seems to be ignored by API ??
		$color				= $this->params->def('color','#3B5998');
		$player_height		= $this->params->def('player_height','200');
		if($mode==1)
			$regex			= '/{https?:\/\/soundcloud.com\/+(.*?)}/i';   //{http://soundcloud.com/blabla/moi}
		elseif($mode==2)
			$regex			=   '/https?:\/\/soundcloud.com\/+(.*?)\s/i'; //http://soundcloud.com/blabla/moi
		elseif($mode==3)
			$regex			= '/{soundcloud\s+(.*?)}/i';   //{http://soundcloud.com/blabla/moi}
		$matches	= array();
		// find all instances of plugin and put in $matches
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		foreach ($matches as $match) {
			//if(substr_count($match[1] ,'/')==1)
				//$player_height='81';
			// $match[0] is full pattern match, $match[1] is the position
			$output = $this->_load($match[1],$mode,$auto_play,$visual,$player_height,$show_comments,$show_description,$show_artwork,$hide_related,$color);
			// We should replace only first occurrence in order to allow positions with the same name to regenerate their content:
			$article->text = preg_replace("|$match[0]|", $output, $article->text, 1);
		}
	}
	protected function _load($url,$mode,$auto_play,$visual,$player_height,$show_comments,$show_description,$show_artwork,$hide_related,$color)
	{
		$url=urlencode($url);		
		$html = '<div>';
		
		$zurl = 'https://soundcloud.com/oembed?url=https%3A//soundcloud.com/'.$url;
		if( $auto_play)
				$zurl =  $zurl."&auto_play=true";
		else
				$zurl =  $zurl."&auto_play=false";
		if( $show_comments)
				$zurl =  $zurl."&show_comments=true";
		else
				$zurl =  $zurl."&show_comments=false";	
		if( $hide_related)
				$zurl =  $zurl."&hide_related=true";
		else
				$zurl =  $zurl."&hide_related=false";
		
		if( $show_artwork)
				$zurl =  $zurl."&show_artwork=true";
		else
				$zurl =  $zurl."&show_artwork=false";
		if( $visual)
				$zurl =  $zurl."&visual=true";
		else
				$zurl =  $zurl."&visual=false";	
					
		$color	= trim($color);
		if( !empty($color))
				$zurl .= "&color=".str_replace('#','',$color);
		$zurl .= "&maxheight=".$player_height ;
		$zurl;
		$result =   simplexml_load_file($zurl);
		if( $show_description )
				$html .= $result->description;
				
		$result_html = $result->html;
		if($visual == 0)
			$result_html = str_replace('?visual=true&','?', $result_html);

		///////////////////////////// debug
				
			/*$string = $result_html;
			$string = " ".$string;
			$ini = strpos($string,'src="');
			if ($ini == 0) return "";
			$ini += strlen('src="');
			$len = strpos($string,'"></iframe>',$ini) - $ini;
			echo $src = substr($string,$ini,$len);*/

		/////////////////////////////
		
			
		$html .= $result_html;
		$html .= '</div>';
		$return = $html;
		return $return;
	}
}
