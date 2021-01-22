<?php
declare(strict_types=1);
namespace Module\Video\Service\Api\Admin;

class HomeController extends Controller {

    public function indexAction() {
        return $this->render([
            '一些统计信息'
        ]);
    }
}