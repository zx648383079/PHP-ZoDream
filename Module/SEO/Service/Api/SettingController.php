<?php
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Events\OptionUpdated;
use Module\SEO\Domain\Repositories\SEORepository;
use Zodream\Infrastructure\Http\Request;

class SettingController extends Controller {

    public function indexAction() {
        $group_list = OptionModel::where('parent_id', 0)
            ->where('type', 'group')
            ->orderBy('position', 'asc', 'id', 'asc')->all();
        return $this->renderData($group_list);
    }

    public function saveAction(Request $request) {
        SEORepository::saveNewOption($request->get('field'));
        SEORepository::saveOption($request->get('option'));
        event(new OptionUpdated());
        return $this->renderData(true);
    }

    public function infoAction($id) {
        return $this->render(OptionModel::find($id));
    }

    public function updateAction($id) {
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

    public function deleteAction($id) {
        OptionModel::where('id', $id)->delete();
        event(new OptionUpdated());
        return $this->renderData(true);
    }

}