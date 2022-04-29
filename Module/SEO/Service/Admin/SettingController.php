<?php
namespace Module\SEO\Service\Admin;

use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Events\OptionUpdated;
use Module\SEO\Domain\Repositories\OptionRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class SettingController extends Controller {

    public function indexAction() {
        $group_list = OptionModel::where('parent_id', 0)
            ->where('type', 'group')
            ->orderBy('position', 'asc', 'id', 'asc')->all();
        return $this->show(compact('group_list'));
    }

    public function saveAction(Request $request) {
        try {
            OptionRepository::saveNewOption($request->get('field'));
        } catch (\Exception $ex) {}
        OptionRepository::saveOption($request->get('option'));
        event(new OptionUpdated());
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function infoAction(int $id) {
        return $this->renderData(OptionModel::find($id));
    }

    public function updateAction(int $id) {
        $model = OptionModel::find($id);
        $model->load();
        if (OptionModel::where('name', $model['name'])->where('id', '<>', $id)->count() > 0) {
            return $this->renderFailure('名称重复');
        }
        if ($model->save()) {
            return $this->renderData([
                'refresh' => true
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction(int $id) {
        OptionRepository::remove($id);
        event(new OptionUpdated());
        return $this->renderData([
            'refresh' => true
        ]);
    }
}