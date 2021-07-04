<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\ShippingRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ShippingController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            ShippingRepository::getList($keywords)
        );
    }

    public function pluginAction() {
        $items = ShippingRepository::getPlugins();
        $data = [];
        foreach ($items as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $this->renderData($data);
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ShippingRepository::get($id, true)
            );
        } catch (\Exception $exception) {
            return $this->renderFailure($exception->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            return $this->render(
                ShippingRepository::save($input->get())
            );
        } catch (\Exception $exception) {
            return $this->renderFailure($exception->getMessage());
        }
    }

    public function deleteAction(int $id) {
        ShippingRepository::remove($id);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            ShippingRepository::all()
        );
    }
}