<?php

class Node{
	
	protected $url;
	protected $depth;
	protected $children = array();
	
	public function __construct($url, $depth){
		$this->url = $url;
		$this->depth = $depth;
	}
	
	public function getUrl(){
		return $this->url;
	}

	public function getDepth(){
		return $this->depth;
	}
	
	public function getChildren(){
		return $this->children;
	}
	
	public function setDepth($depth){
		$this->depth = $depth;
	}
	
	public function addChild($child_url){
		$children[] = $child_url;
	}
	
	public function toFullUrl($url){
		
		if(strcmp((substr($url, 0, 1)), '/') == 0){
			$url = WebCrawler::getBaseUrl() . $url; 
		}
		
		return $url;
	}
	
	public function buildTree(){
	
		$url = $this->url;
	
		/* only crawl link if it is not a link to facebook or twitter and 
			has not been visited already */
		if((strpos($url,'twitter') !== false) || (strpos($url,'facebook') !== false) 
			|| ((in_array($url, WebCrawler::getVisited())))){
			
			return;
		}
			
		//grab the contents of this page
		$html = file_get_html($url);
		
		
		//make sure this page exists before processing
		if($html)
			
			//crawl all links on page that haven't been visited yet
			foreach($html->find("a") as $link){
				
				if(!in_array($link, WebCrawler::getVisited())){
					//mark as visited
					WebCrawler::addVisited($url);
					$new_child = new Node($this->toFullUrl($link->href), $this->depth + 1);
					$this->addChild($new_child);
					
					//now traverse this new link
					$new_child->buildTree();
				}
			}
		}
		
	
}

?>