<?php
namespace Module\Legwork\Service\Api;

use Module\Auth\Domain\Concerns\CheckRole;
use Module\Legwork\Domain\Repositories\RunnerRepository;

class RunnerController extends Controller {

    use CheckRole;

    public function rules() {
        return [
            '*' => 'shop_admin'
        ];
    }

    public function indexAction() {
        $data = RunnerRepository::waitTakingList();
        return $this->renderPage($data);
    }

    public function orderAction($status = 0) {
        $data = RunnerRepository::getOrders($status);
        return $this->renderPage($data);
    }

    public function takingAction($id) {
        try {
            RunnerRepository::taking($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'data' => true
        ]);
    }

    public function takenAction($id) {
        try {
            RunnerRepository::taken($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render([
            'data' => true
        ]);
    }

}