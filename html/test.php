<?php
//define('DEBUG', true);
//define('APP_DIR', dirname(dirname(__FILE__)));
//require_once(APP_DIR.'/vendor/autoload.php');

use Zodream\Image\Node\CircleNode;
use Zodream\Service\Application;
use Zodream\Image\Node\BoxNode;
use Zodream\Image\Node\ImgNode;
use Zodream\Image\Node\TextNode;
use Zodream\Image\Node\BorderNode;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Application(APP_DIR);