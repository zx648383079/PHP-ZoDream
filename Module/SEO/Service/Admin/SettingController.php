<?php
namespace Module\SEO\Service\Admin;

use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Events\OptionUpdated;
use Module\SEO\Domain\Repositories\SEORepository;
use Zodream\Infrastructure\Http\Request;

class SettingController extends Controller {

    public function indexAction() {
        $group_list = OptionModel::where('parent_id', 0)
            ->where('type', 'group')
            ->orderBy('position', 'asc', 'id', 'asc')->all();
        return $this->show(compact('group_list'));
    }

    public function saveAction(Request $request) {
        try {
            SEORepository::saveNewOption($request->get('field'));
        } catch (\Exception $ex) {}
        SEORepository::saveOption($request->get('option'));
        event(new OptionUpdated());
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function infoAction($id) {
        return $this->jsonSuccess(OptionModel::find($id));
    }

    public function updateAction($id) {
        $model = OptionModel::find($id);
        $model->load();
        if (OptionModel::where('name', $model['name'])->where('id', '<>', $id)->count() > 0) {
            return $this->jsonFailure('名称重复');
        }
        if ($model->save()) {
            return $this->jsonSuccess([
                'refresh' => true
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        OptionModel::where('id', $id)->delete();
        event(new OptionUpdated());
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}