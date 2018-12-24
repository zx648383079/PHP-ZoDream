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


$p = (new \Zodream\Infrastructure\Pipeline\MiddlewareProcessor())
    ->process(1, function ($p, $next) {

        return $next($p * 2);
    }, function ($p, $next) {
        $p = $next($p);
        return $p . '3';
    }, function ($p, $next) {
        return $next($p . '这是');
    });

dd($p);

$goods = new File('D:\Documents\Github\PHP-ZoDream\html\assets\images\zd.jpg');
$qr = new File('D:\Documents\Github\PHP-ZoDream\html\assets\images\wx.jpg');

$font = 'D:\Documents\Github\PHP-ZoDream\data\fonts\msyh.ttc';

$img = new Canvas();
$img->create(402, 712);
$img->setBackground('#fff')
    ->addImage(new Image($goods), new Box(0, 60, 402, 402))
    ->addImage(new Image($qr), new Box(18, 590, 106, 106))
    ->addText(new Text('请长按识别二维码', 18, 560, [155, 143, 128], $font, 12))
    ->addText(new Text('微信支付购买', 18, 576, [155, 143, 128], $font, 12))
    ->addText(new Text('kiwigo', 300, 560, '#000', $font, 16))
    ->addText(new Text('￥', 278, 640, '#f00', $font, 12))
    ->addText(new Text('123.00', 290, 640, '#f00', $font, 25))
    ->addText(new Text('测试商品', 300, 670, '#000', $font, 12))
;
app('response')->image($img)->send();