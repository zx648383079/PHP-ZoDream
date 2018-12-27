<?php
namespace Infrastructure;


use Zodream\Helpers\Str;

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
		$content = preg_replace('/(\<.+?\>)|(\&nbsp;)+/', '', htmlspecialchars_decode($content));
		return Str::subString($content, 0, $length);
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

	/**
	 * 根据相同分组
	 * @param array $args
	 * @param string|object $tag 如果为匿名函数, 传入两个数组, 相等返回true 否则false
	 * @return array
	 */
	public static function getTree(array $args, $tag) {
		if (is_string($tag)) {
			$tag = function(array $arg, array $composer) use ($tag) {
				return $arg[$tag] === $composer[$tag];
			};
		}
		$results = array();
		$result = array();
		foreach ($args as $arg) {
			if (empty($result) || !$tag($arg, $result[0])) {
				$results[] = $result;
				$result = array($arg, array());
			}
			$result[1][] = $arg;
		}
		$results[] = $result;
		unset($results[0]);
		return $results;
	}
}