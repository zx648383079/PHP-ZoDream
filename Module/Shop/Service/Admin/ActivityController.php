<?php
namespace Module\Shop\Service\Admin;

class ActivityController extends Controller {

    public function indexAction() {
        return $this->show();
    }

    /**
     * 抽奖
     */
    public function lotteryAction() {
        return $this->show();
    }
}