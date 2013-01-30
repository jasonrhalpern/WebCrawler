<?php


require 'simple_html_dom.php';
require 'Node.php';
require 'WebCrawler.php';

$base_url = 'http://www.gocardless.com/';
$crawler = new WebCrawler($base_url);
$crawler->crawl();
$crawler->printSitemap();


?>