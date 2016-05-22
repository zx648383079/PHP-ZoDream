<?php
namespace Service\Home;
/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/5/22
 * Time: 13:11
 */
class LaboratoryController extends Controller {
    public function indexAction() {
        $this->show(array(
            'title' => '实验室'
        ));
    }
}