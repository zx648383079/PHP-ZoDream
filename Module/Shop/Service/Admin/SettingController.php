<?php
namespace Module\Shop\Service\Admin;



class SettingController extends Controller {

    public function indexAction() {
        $group_list = [];
        return $this->show(compact('group_list'));
    }
}