<?php
namespace Infrastructure;

use Zodream\Infrastructure\ObjectExpand\StringExpand;
class HtmlExpand {
	public static function getImage($content, $default = '/assets/home/images/default.jpg') {
		$match = array();
		if (preg_match('/\<img[^<>]+src="([^"<>\s]+)"/i', $content, $match)) {
			return $match[1];
		}
		return $default;
	}

	public static function getVideo($content, $default = '/assets/home/video/default.mp4') {
		$match = array();
		if (preg_match('/\<video[^<>]+src="([^"<>\s]+)"/i', $content, $match)) {
			return $match[1];
		}
		return $default;
	}
	
	public static function shortString($content, $length = 100) {
		$content = preg_replace('/(\<.+?\>)|(\&nbsp;)+/', '', $content);
		return StringExpand::csubstr($content, 0, $length);
	}

	public static function getMenu(array $data) {
		$class = null;
		foreach ($data as $value) {
			if ($value['class'] != $class) {
				if (!is_null($class)) {
					echo '</ul></li>';
				}
				$class = $value['class'];
				echo '<li>',$class,'<ul>';
			}
			if ($value['class'] === $class) {
				echo '<li data="',$value['url'],'">',$value['name'],'</li>';
			}
		}
		if (!empty($data)) {
			echo '</ul></li>';
		}
	}
}