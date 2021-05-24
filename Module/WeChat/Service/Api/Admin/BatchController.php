<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {

    use BatchAction;

    public function indexAction() {
        return $this->render($this->invokeBatch([

        ]));
    }

}