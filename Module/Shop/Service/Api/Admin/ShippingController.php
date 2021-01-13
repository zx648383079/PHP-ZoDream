<?php
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Models\ShippingGroupModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Models\ShippingRegionModel;
use Module\Shop\Domain\Repositories\ShippingRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ShippingController extends Controller {

    public function indexAction() {
        $model_list = ShippingModel::page();
        return $this->renderPage($model_list);
    }

    public function pluginAction() {
        $items = ShippingRepository::getPlugins();
        $data = [];
        foreach ($items as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $this->renderData($data);
    }

    public function detailAction($id) {
        try {
            return $this->render(
                ShippingRepository::get($id)
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

    public function deleteAction($id) {
        ShippingRepository::remove($id);
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            ShippingModel::query()->get('id', 'name')
        );
    }
}