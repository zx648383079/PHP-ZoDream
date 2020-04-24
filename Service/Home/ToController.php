<?php
namespace Service\Home;

use Module\Counter\Domain\Events\JumpOut;
use Zodream\Infrastructure\Http\Response;

class ToController extends Controller {
    public $layout = false;

    /**
     * 跳出链接
     * @path /to
     * @method get
     * @param string $url
     * @return Response
     * @throws \Exception
     */
    public function indexAction(string $url = null) {
        if (!empty($url)) {
            $url = base64_decode($url.'=');
            event(JumpOut::create($url));
        }
        if (empty($url)) {
            $url = url('/');
        }
        return $this->show(compact('url'));
    }

    public static function to($url) {
        return url('/to', ['url' => rtrim(base64_encode($url), '=')]);
    }
}