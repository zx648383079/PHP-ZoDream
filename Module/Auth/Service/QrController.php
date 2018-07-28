<?php
namespace Module\Auth\Service;

use Module\ModuleController;
use Zodream\Image\QrCode;

class QrController extends ModuleController {

    public function indexAction() {
        $image = new QrCode();
        $image->encode('11111111111111111');
        return app('response')->image($image);
    }

    public function findAction() {
        return $this->show([
            'title' => '找回密码'
        ]);
    }
}