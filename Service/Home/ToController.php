<?php
namespace Service\Home;

use Module\Counter\Domain\Events\JumpOut;

class ToController extends Controller {
    public $layout = false;

    public function indexAction($url = null) {
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