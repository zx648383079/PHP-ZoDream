<?php
namespace Module\RPC\Service;

use Module\RPC\Domain\Server;

class ServerController extends Controller {

    public function indexAction() {
        $server = new Server();
        return $this->json($server->handle());
    }



}