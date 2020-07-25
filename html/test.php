<?php
define('DEBUG', true);
define('APP_DIR', dirname(dirname(__FILE__)));
require_once(APP_DIR.'/vendor/autoload.php');

use Zodream\Helpers\Xml;
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
use Zodream\Image\Node\BoxNode;
use Zodream\Image\Node\ImgNode;
use Zodream\Image\Node\TextNode;
use Zodream\Image\Node\BorderNode;
use Zodream\Image\Node\LineNode;
use Zodream\Image\Node\RectNode;
use Module\Document\Domain\CodeParser;
