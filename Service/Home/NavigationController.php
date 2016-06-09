<?php
namespace Service\Home;

use Domain\Model\EmpireModel;
use Infrastructure\HtmlExpand;
use Zodream\Domain\Authentication\Auth;
use Zodream\Infrastructure\Request;

class NavigationController extends Controller {
    function indexAction() {
        $data = EmpireModel::query('navigation n')->findAll(array(
            'left' => array(
                'navigation_category c',
                'c.id = n.category_id'
            ),
            'order' => 'c.name,c.position,n.position'
        ), 'n.name as name,n.url as url,c.name as category');
        $category = EmpireModel::query('navigation_category')->findAll(array(
            'order' => 'position'
        ), 'id,name');
        $this->show(array(
            'data' => HtmlExpand::getTree($data, 'category'),
            'category' => $category
        ));
    }

    function indexPost() {
        if (Auth::guest()) {
            return;
        }
        if (empty(Request::post('category_id'))) {
            EmpireModel::query('navigation_category')->save(array(
                'name' => 'required|string:1-20',
                'user_id' => ''
            ));
            return;
        }
        EmpireModel::query('navigation')->save(array(
            'name' => 'required|string:1-100',
            'url' => 'required|string:1-255',
            'category_id' => 'required|int',
            'user_id' => ''
        ));
    }
}