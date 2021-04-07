<?php
declare(strict_types=1);
namespace Infrastructure;

use Service\Home\ToController;
use Zodream\Helpers\Str;
use Zodream\Html\MarkDown;

class HtmlExpand {

    public static function toUrl(string $url): string {
        if (!str_contains($url, '//')) {
            return $url;
        }
        if (str_contains($url, request()->host())) {
            return $url;
        }
        return ToController::to($url);
    }


    public static function toHtml($content, $isMarkDown = false) {
        if ($isMarkDown) {
            $content = MarkDown::parse($content, true);
        }
        return preg_replace_callback('/<a[^\<\>]+?href="([^"<>\s]+)"/', function ($match) {
            if (!str_contains($match[1], '//')) {
                return $match[0];
            }
            if (str_contains($match[1], request()->host())) {
                return $match[0];
            }
            return str_replace($match[1], static::toUrl($match[1]), $match[0]);
        }, $content);
    }


	public static function getImage($content, $default = '/assets/images/default.jpg') {
		$match = array();
		if (preg_match('/\<img[^<>]+src="([^"<>\s]+)"/i', $content, $match)) {
			return $match[1];
		}
		return $default;
	}

	public static function getVideo($content, $default = '/assets/video/default.mp4') {
		$match = array();
		if (preg_match('/\<video[^<>]+src="([^"<>\s]+)"/i', $content, $match)) {
			return $match[1];
		}
		return $default;
	}
	
	public static function shortString($content, $length = 100) {
		$content = preg_replace('/(\<.+?\>)|(\&nbsp;)+/', '', htmlspecialchars_decode($content));
		return Str::substr($content, 0, $length);
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

    /**
     * 截取html, 标签不计入长度，自动闭合标签
     * @param string $html
     * @param int $length
     * @param string $endWith
     * @bug 本方法缺陷： 未进行严格标签判断 例如 < <gg data="<a>"
     * @example ::substr('<p>1111<div/>111<br>111<i class="444">11</i>111 55555</p>', 12)
     * @return string
     */
	public static function substr(string $html, int $length, string $endWith = '...'): string {
        if ($length < 1) {
            return $endWith;
        }
        $maxLength = mb_strlen($html);
        if ($maxLength < $length) {
            return $html;
        }
        $result = '';
        $n = 0;
        $unClosedTags = [];
        $isCode = false; // 是不是HTML代码
        $isHTML = false; // 是不是HTML特殊字符,如
        $notClosedTags = ['area', 'base', 'basefont', 'br', 'col', 'frame', 'hr', 'img', 'input', 'link', 'meta', 'param', 'embed', 'command', 'keygen', 'source', 'track', 'wbr'];
        $tag = '';
        for ($i = 0; $i < $maxLength; $i++) {
            $char = mb_substr($html, $i, 1);
            if ($char == '<') {
                // 进入标签
                $isCode = true;
                $tag = '';
            }
            else if ($char == '&') {
                $isHTML = true;
            }
            else if ($char == '>' && $isCode) {
                $n = $n - 1;
                $isCode = false;
                $tag = explode(' ', $tag, 2)[0];
                if (substr($tag, 0, 1) === '/') {
                    // 判断是否时结束标签， 倒序找到邻近开始标签，进行移除
                    for ($j = count($unClosedTags) - 1; $j >= 0; $j --) {
                        if ($tag === $unClosedTags[$j]) {
                            $unClosedTags = array_splice($unClosedTags, 0, $j - 1);
                            break;
                        }
                    }
                    $tag = '';
                }
                if (!empty($tag) &&
                    substr($tag, strlen($tag) - 1, 1) !== '/'
                    && !in_array(strtolower($tag), $notClosedTags)) {
                    // 不是结束标签且不是自闭合且不是无需闭合把标签加入
                    $unClosedTags[] = $tag;
                }
                $tag = '';
            }
            else if ($char == ';' && $isHTML) {
                $isHTML = false;
            }
            if ($isCode && ($tag !== '' || $char !== '<')) {
                $tag .= $char;
            }
            if (!$isCode && !$isHTML && $char !== ' ') {
                $n = $n + 1;
            }
            $result .= $char;
            if ($n >= $length) {
                break;
            }
        }
        $result .= $endWith;
        for ($j = count($unClosedTags) - 1; $j >= 0; $j --) {
            $result .= sprintf('</%s>', $unClosedTags[$j]);
        }
        return $result;
    }
}