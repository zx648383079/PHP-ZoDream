<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Service\Api;

use Zodream\Infrastructure\Contracts\Http\Input;

class KeepAliveController extends Controller {

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction(Input $input) {


        return $this->render([]);
    }
}