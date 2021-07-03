<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Middleware;

use Module\Blog\Domain\Helpers\RouterHelper;
use Zodream\Http\Uri;
use Zodream\Route\Rewrite\URLEncoder;

class BlogSeoEncoder implements URLEncoder {

    public function decode(Uri $url, callable $next): Uri {
        $path = $url->getPath();
        if (!str_starts_with($path, 'blog/') || strpos($path, '/', 5) !== false) {
            return $next($url);
        }
        $blogId = RouterHelper::linkId(substr($path, 5));
        if ($blogId < 1) {
            return $next($url);
        }
        return $url->addData('id', $blogId)->setPath('blog');
    }

    public function encode(Uri $url, callable $next): Uri {
        $path = trim($url->getPath(), '/');
        if (!str_starts_with($path, 'blog')) {
            return $next($url);
        }
        if ($path !== 'blog' && $path !== 'blog/home/detail') {
            return $next($url);
        }
        $link = RouterHelper::idLink($url->getData('id').'');
        if (empty($link)) {
            return $next($url);
        }
        return $url->setPath('blog/'.$link)->removeData('id');
    }
}