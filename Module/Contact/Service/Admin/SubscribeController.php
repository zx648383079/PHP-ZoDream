<?php
declare(strict_types=1);
namespace Module\Contact\Service\Admin;

use Module\Contact\Domain\Repositories\SubscribeRepository;

class SubscribeController extends Controller {
    public function indexAction() {
        $model_list = SubscribeRepository::getList();
        return $this->show(compact('model_list'));
    }

}