<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\DeliveryRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class DeliveryController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            DeliveryRepository::getList($keywords)
        );
    }

    public function saveAction(Input $input, int $id) {
        try {
            return $this->render(
                DeliveryRepository::save($id, $input->all())
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            DeliveryRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}