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
use Module\Shop\Domain\Models\RegionModel;
use Zodream\Image\Canvas;
use Zodream\Image\Image;
use Zodream\Disk\File;
use Zodream\Image\Node\Box;
use Zodream\Image\Node\Point;
use Zodream\Image\Node\Text;

$dir = new \Zodream\Disk\Directory('E:\Desktop\test');

$spider = new \Module\Book\Domain\Spiders\AutoSpider();


$dir->map(function ($file) use ($spider) {
    if ($file instanceof File && $file->getExtension() === 'html') {
        $content = $spider->getCleanContent($file->read());
        $file->getDirectory()
            ->addFile($file->getNameWithoutExtension().'.json',
                \Zodream\Helpers\Json::encode($content));
    }
});