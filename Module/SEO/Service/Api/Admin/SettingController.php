<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api\Admin;

use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Events\OptionUpdated;
use Module\SEO\Domain\Repositories\OptionRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SettingController extends Controller {

    public function indexAction() {
        return $this->renderData(OptionRepository::getEditList());
    }

    public function saveAction(array $option = [], array $field = []) {
        try {
            OptionRepository::saveNewOption($field);
        } catch (\Exception $ex) {}
        OptionRepository::saveOption($option);
        event(new OptionUpdated());
        return $this->renderData(true);
    }

    public function detailAction(int $id) {
        return $this->render(OptionModel::find($id));
    }

    public function updateAction(Input $input, int $id) {
        try {
            $model = OptionRepository::update($id, $input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveOptionAction(Input $request, int $id = 0) {
        if ($id > 0) {
            return $this->updateAction($request, $id);
        }
        try {
            $model = OptionRepository::saveNewOption($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        event(new OptionUpdated());
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        OptionRepository::remove($id);
        event(new OptionUpdated());
        return $this->renderData(true);
    }

}