<?php
namespace Service\Admin;
/**
 * 后台首页
 */
use Module\SEO\Domain\Listeners\SiteMapListener;

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

    public function sitemapAction() {
	    $map = SiteMapListener::create();
        return $this->show(compact('map'));
    }

    public function cacheAction() {
	    cache()->delete();
	    return $this->show();
    }
}