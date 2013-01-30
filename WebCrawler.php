<?php

class WebCrawler{

	protected static $root;
	protected static $visited = array();
	
	public function __construct($base_url){
		WebCrawler::$root = new Node($base_url, 0);
	}
	
	public static function getBaseUrl(){
		return WebCrawler::$root->getUrl();
	}
	
	public function crawl(){
		WebCrawler::$root->buildTree();
	}
	
	public function printSitemap(){
	
		$q = array();
		
		//enqueue root on queue
		array_push($q, WebCrawler::$root);
		
		//breadth first search on the tree
		while(!empty($q)){
			//dequeue first item
			$node = array_shift($q);
			//indent the sitemap to account for the depth
			WebCrawler::printIndent($node->getDepth());
			echo $node->getUrl()."\n";
			WebCrawler::printStaticAssets($node->getUrl(), $node->getDepth());
			//enqueue all the children
			foreach($node->getChildren() as $child){
				array_push($q, $child);
			}
		}
	}
	
	public function printStaticAssets($url, $depth){
	
		$html = file_get_html($url);
	
		//css files
		foreach($html->find("link") as $link){
			WebCrawler::printIndent($depth);
			echo $link->href."\n";
		}

		//javascript files
		foreach($html->find("script") as $link){
			WebCrawler::printIndent($depth);
			echo $link->href."\n";
		}

		//images
		foreach($html->find("img") as $link){
			WebCrawler::printIndent($depth);
			echo $link->href."\n";
		}
	}
	
	public function printIndent($depth){
		for ($i=0; $i < $depth; $i++){
			echo "\t";
		}
	}
	
	public static function addVisited($node){
		WebCrawler::$visited[] = $node;
	}
	
	public static function getVisited(){
		return self::$visited;
	}
	
}

?>