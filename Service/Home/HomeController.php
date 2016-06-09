<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Infrastructure\HtmlExpand;
use Zodream\Domain\Authentication\Auth;
use Zodream\Infrastructure\Request;

class HomeController extends Controller {
    function indexAction() {
        $data = EmpireModel::query('post')->findAll([
            'limit' => 4,
            'order' => 'recommend desc'
        ]);
        $this->show(array(
            'title' => '首页',
            'data' => $data
        ));
    }

    function aboutAction() {
        $this->show(array(
            'title' => '关于',
        ));
    }
}