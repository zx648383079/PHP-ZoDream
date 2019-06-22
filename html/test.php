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
use Zodream\Image\Node\BoxNode;
use Zodream\Image\Node\ImgNode;
use Zodream\Image\Node\TextNode;
use Zodream\Image\Node\BorderNode;
use Zodream\Image\Node\LineNode;
use Zodream\Image\Node\RectNode;

$str = <<<TEXT



[padding=10 background=#fff width=470]
[img width=450 height=450]aaa
[size=20 padding=10,0 bold]sbfajahaa
[color=#ccc size=12]sdfsssdfdafsdaasfs
[img width=100 height=100 center]iadfasdsad
[size=10 color=#ccc center]123131231
TEXT;
$box = BoxNode::parse($str);

dd($box);
$img = __DIR__.'/assets/images/banner.jpg';
$font = __DIR__.'/../data/fonts/msyh.ttc';
$box = BoxNode::create([
    'padding' => 10,
    'background' => 'white',
    'width' => 470
])->append(
    ImgNode::create($img, [
        'width' => '100%',
        'height' => '100%'
    ]),
    TextNode::create('sbfajahaa', [
        'size' => 20,
        'letterSpace' => 20,
        'padding' => [
            10,
            0,
        ],
        'bold' => true,
        'font' => $font
    ]),
    TextNode::create('1234avccg', [
        'size' => 12,
        'font' => $font,
        'letterSpace' => 4,
        'lineSpace' => 4,
        'color' => '#ccc'
    ]),
    ImgNode::create($img, [
        'width' => '100',
        'height' => '100',
        'center' => true
    ]),
    TextNode::create('sbfajahaa', [
        'size' => 12,
        'color' => '#ccc',
        'letterSpace' => 4,
        'lineSpace' => 4,
        'wrap' => false,
        'font' => $font,
        'center' => true
    ]),
    BorderNode::create([
        'size' => 1,
        'fixed' => true,
        'margin' => 10
    ]),
    LineNode::create(10, 10, 10, 100, [
        'size' => 1,
        'fixed' => true,
        'color' => 'black'
    ]),
    RectNode::create([
        'points' => [
            [0, 0],
            [200, 0],
            [0, 200],
        ],
        'color' => 'black'
    ])
);
$box->draw()->show();