<?php
/**
 * Rss
 * Created by PhpStorm.
 * Author: Xu shantong <shantongxu@qq.com>
 * Date: 19-7-26
 * Time: 下午6:20
 */

namespace common\helpers;


class Rss
{
	public $title = '徐善通的随笔';
	
	public $description = '徐善通的博客';
	
	public $link = 'http://xstnet.com';
	
	public $language = 'zh-cn';
	
	public $copyright = 'Copyright 2016,2020 xstnet.com';
	
	public $pubDate;
	
	public $generator = 'xstnet.com';
	
	public $items = [];
	
	public function __construct($data = [])
	{
		$this->setData($data);
	}
	
	public function setData($data)
	{
//		$this->pubDate = date("D, d M Y H:i:s ", strtotime($date)) . "GMT";
		foreach ($data as $key => $value) {
			$this->$key =  $value;
		}
	}
	
	public function __set($name, $value)
	{
	
	}
	
	public function setItem(array $item)
	{
		$this->items[] = $item;
	}
	
	public function renderRss() : string
	{
		return $this->renderHeader() . $this->renderItems() . $this->renderFooter();
	}
	
	public function renderItems()
	{
		$xml = '';
		foreach ($this->items as $data) {
			$xml .= "\t<item>\n\t";
			foreach ($data as $key => $value) {
				$xml .= "\t";
				switch ($key) {
					case 'title' :
						$xml .= sprintf('<title><![CDATA[ %s ]]></title>', $value);
						break;
					case 'description' :
						$xml .= sprintf('<description><![CDATA[ %s ]]></description>', $value);
						break;
					case 'link' :
						$xml .= sprintf('<link>%s</link>', $value);
						break;
					case 'pubDate' :
						$xml .= sprintf('<pubDate>%s</pubDate>', $value);
						break;
					case 'guid' :
						$xml .= sprintf('<guid>%s</guid>', $value);
						break;
				}
				$xml .= "\n\t";
			}
			$xml .= "</item>\n";
		}
		
		return $xml;
	}
	
	public function renderHeader() : string
	{
		$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
<rss version=\"2.0\">
<channel>
	<title>{$this->title}</title>
	<description>{$this->description}</description>
	<link>{$this->link}</link>
	<generator>{$this->generator}</generator>
	<image>
		<url>http://xstnet.com/favicon.ico</url>
		<title>徐善通的随笔</title>
		<link>http://xstnet.com</link>
	</image>\n";
		
		return $xml;
	}
	
	public function renderFooter() : string
	{
		return "</channel>\n</rss>";
	}
}