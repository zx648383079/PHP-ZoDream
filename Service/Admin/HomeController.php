<?php
declare(strict_types=1);
namespace Service\Admin;
/**
 * 后台首页
 */
class HomeController extends Controller {

    public function indexAction() {
        $user = auth()->user();
        return $this->show(array(
//            'name' => $user['name'],
//            'num' => $user['login_num'],
//            'ip' => $user['previous_ip'],
//            'date' => $user['previous_at'],
//            'search' => $search
        ));
    }
}