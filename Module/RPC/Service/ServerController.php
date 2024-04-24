<?php
declare(strict_types = 1);
namespace Module\RPC\Service;

use Module\RPC\Domain\Server;

class ServerController extends Controller {

    public function indexAction() {
        $server = new Server();
        return $this->renderResponse($server->handle());
    }



}