<?php
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Events\OptionUpdated;
use Module\SEO\Domain\Repositories\SEORepository;
use phpDocumentor\Reflection\Types\This;
use Zodream\Infrastructure\Http\Request;

class SettingController extends Controller {

    public function indexAction() {
        /** @var OptionModel[] $group_list */
        $group_list = OptionModel::with('children')->where('parent_id', 0)
            ->where('type', 'group')
            ->orderBy('position', 'asc', 'id', 'asc')->all();
        foreach ($group_list as $group) {
            $group->setAppend('children');
        }
        return $this->renderData($group_list);
    }

    public function saveAction(Request $request) {
        try {
            SEORepository::saveNewOption($request->get('field'));
        } catch (\Exception $ex) {}
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

    public function saveOptionAction(Request $request) {
        $id = intval($request->get('id'));
        if ($id > 0) {
            return $this->updateAction($id);
        }
        try {
            $model = SEORepository::saveNewOption($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        event(new OptionUpdated());
        return $this->render($model);
    }

    public function deleteAction($id) {
        OptionModel::where('id', $id)->delete();
        OptionModel::where('parent_id', $id)->delete();
        event(new OptionUpdated());
        return $this->renderData(true);
    }

}