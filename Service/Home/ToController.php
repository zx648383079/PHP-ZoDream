<?php
declare(strict_types=1);
namespace Service\Home;

use Infrastructure\JumpTo;
use Module\Counter\Domain\Events\JumpOut;
use Zodream\Disk\File;
use Zodream\Helpers\Html;

class ToController extends Controller {
    protected File|string $layout = '';

    /**
     * 跳出链接
     * @path /to
     * @method get
     * @param string $url
     * @throws \Exception
     */
    public function indexAction(string $url = '') {
        $autoJump = true;
        $isValid = true;
        if (!empty($url)) {
            $url = JumpTo::decode($url);
            $autoJump = $isValid = JumpTo::isValid($url);
            event(JumpOut::create($url));
        }
        if (empty($url)) {
            $url = url('/');
        }
        $encodeUrl = Html::text($url);
        $url = Html::text(urldecode($url));
        response()->header('X-Robots-Tag', 'noindex');
        return $this->show(compact('url', 'encodeUrl', 'autoJump', 'isValid'));
    }
}