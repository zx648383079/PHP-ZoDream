<?php
namespace Module\Shop\Service\Mobile;

use Zodream\Image\QrCode;

class AffiliateController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->show();
    }

    public function orderAction() {
        return $this->show();
    }

    public function userAction() {
        return $this->show();
    }

    public function shareAction() {
        return $this->show();
    }

    public function ruleAction() {
        return $this->show();
    }

    public function qrAction() {
        $image = new QrCode();
        $image->encode($this->getUrl('', ['u' => auth()->id()]));
        return app('response')->image($image);
    }
}