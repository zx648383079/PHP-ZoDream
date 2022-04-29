<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api\Admin;

use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Events\OptionUpdated;
use Module\SEO\Domain\Repositories\OptionRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class SettingController extends Controller {

    public function indexAction() {
        return $this->renderData(OptionRepository::getEditList());
    }

    public function saveAction(Request $request) {
        try {
            OptionRepository::saveNewOption($request->get('field'));
        } catch (\Exception $ex) {}
        OptionRepository::saveOption($request->get('option'));
        event(new OptionUpdated());
        return $this->renderData(true);
    }

    public function infoAction(int $id) {
        return $this->render(OptionModel::find($id));
    }

    public function updateAction(int $id) {
        $model = OptionModel::find($id);
        $model->load();
        if (OptionModel::where('name', $model['name'])->where('id', '<>', $id)->count() > 0) {
            return $this->renderFailure('名称重复');
        }
        if ($model->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function saveOptionAction(Request $request) {
        $id = intval($request->get('id'));
        if ($id > 0) {
            return $this->updateAction($id);
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