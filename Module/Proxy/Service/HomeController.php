<?php
namespace Module\Proxy\Service;

use Module\Proxy\Domain\Spider;
use Zodream\Route\Controller\Controller as RestController;

class HomeController extends RestController {
	
	public function indexAction() {
        $data = cache()->getOrSet('ip_proxy_pool', function () {
            $spider = new Spider();
            return $spider->getProxyPool();
        }, 86400);
		return $this->render($data);
	}
}