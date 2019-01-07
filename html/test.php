<?php
define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');
use Zodream\Spider\Spider;
use Zodream\Spider\Support\Uri;
use Zodream\Template\Engine\ParserCompiler;
use Zodream\Service\Factory;
use Zodream\Route\RouteCompiler;
use Zodream\Database\Relation;
use Module\Document\Domain\Model\ProjectModel;
use Zodream\Http\Http;
use Zodream\Helpers\Str;
use Module\Shop\Domain\Model\RegionModel;
use Zodream\Image\Canvas;
use Zodream\Image\Image;
use Zodream\Disk\File;
use Zodream\Image\Node\Box;
use Zodream\Image\Node\Point;
use Zodream\Image\Node\Text;
$str = '我';
$timer = new \Zodream\Debugger\Domain\Timer();

$timer->record('start...');
for ($i = 0; $i < 100; $i ++) {
    $a = in_array($str, ['/', 'a']);
}
$timer->record('[]');

for ($i = 0; $i < 100; $i ++) {
    $a = $str == '/' || $str == 'a';
}
$timer->record('==');

dd($timer->getTimes());