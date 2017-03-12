<?php
use Zodream\Infrastructure\Disk\File;
use Zodream\Domain\Spider\Spider;
define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
$file = new File('a.txt');
Spider::loadUrl('')->map(function ($html) {
    return (new simple_html_dom($html))->find('', 1)->find('a');
})->switch(function ($url) use ($file) {
    $file->append(Spider::loadUrl($url)->map(function ($html) {
        $dom =  new simple_html_dom($html);
        $title = $dom->find('', 1)->text();
        $content = $dom->find('', 1)->text();
        return "$title\r\n\r\n$content\r\n\r\n\r\n";
    })->getData());
});