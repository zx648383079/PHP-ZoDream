<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;

class FormController extends Controller {

    public function indexAction() {
        $model = $this->getModel();
        $field_list = ModelFieldModel::where('model_id', $model->id)->all();
        $scene = CMSRepository::scene()->setModel($model);
        $inputItems = [];
        foreach ($field_list as $item) {
            $inputItems[] = $scene->toInput($item, [], true);
        }
        return $this->renderData($inputItems);
    }

    public function saveAction() {
        $model = $this->getModel();
        if (empty($model) || $model->type < 1) {
            return $this->renderFailure('表单数据错误');
        }
        $scene = CMSRepository::scene()->setModel($model);
        $id = 0;
        if ($model->setting('is_only')) {
            if (auth()->guest()) {
                return $this->renderFailure('请先登录！');
            }
            $id = $scene->query()
                ->where('model_id', $model->id)
                ->where('user_id', auth()->id())
                ->value('id');
        }
        $data = request()->get();
        if ($id > 0) {
            $res = $scene->update($id, $data);
        } else {
            $res = $scene->insert($data);
        }
        if (!$res) {
            return $this->renderFailure($scene->getFirstError());
        }
        return $this->renderData(true);
    }

    /**
     * @return ModelModel
     * @throws \Exception
     */
    protected function getModel() {
        if (request()->has('id')) {
            return FuncHelper::model(intval(request()->get('id')));
        }
        return FuncHelper::model(request()->get('model'));
    }
}