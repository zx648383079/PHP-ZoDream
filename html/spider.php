<?php
use Zodream\Disk\File;
use Zodream\Domain\Spider\Spider;
use Zodream\Infrastructure\Support\Curl;
use Zodream\Helpers\Html;
use Module\Book\Domain\Model\BookModel;

define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');

class BookSpider {
    public static function site($url) {

    }

    public static function book($urls) {
        if (!is_array($urls)) {
            $urls = func_get_args();
        }
        foreach ($urls as $url) {
            static::getBook($url);
        }
    }

    protected static function getBook($url) {
        $spider = Spider::loadUrl('http://www.biqugeg.com/23_23095/');
        BookModel::create([
            'name' => '',
            'cover' => '',
            'author' => '',
            'description' => ''
        ]);
        $data = $spider->matches('#\<dd\>\<a\s+href\s*="([^"]+)">(.+?)\</a\>\</dd\>#i');
        foreach ($data as $match) {
            echo $match[2],PHP_EOL;
            $file->append($match[2].PHP_EOL);
            $file->append(Spider::loadUrl($match[1])->match('#\<div\s+id="content"\s+class="showtxt"\>([\s\S]+?)\</div\>#')[1]);
            $file->append(PHP_EOL.PHP_EOL);
        }
    }

    protected static function getChapter($book_id, $url) {

    }


}



Spider::site('http://www.biqugeg.com');