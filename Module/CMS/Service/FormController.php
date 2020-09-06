<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Module;

class FormController extends Controller {

    public function indexAction() {
        $model = $this->getModel();
        $field_list = ModelFieldModel::where('model_id', $model->id)->all();
        $scene = CMSRepository::scene()->setModel($model);
        return $this->show(compact('model', 'field_list', 'scene'));
    }

    public function saveAction() {
        $model = $this->getModel();
        if (empty($model) || $model->type < 1) {
            return $this->jsonFailure('表单数据错误');
        }
        $scene = CMSRepository::scene()->setModel($model);
        $id = 0;
        if ($model->setting('is_only')) {
            if (auth()->guest()) {
                return $this->jsonFailure('请先登录！');
            }
            $id = $scene->query()
                ->where('model_id', $model->id)
                ->where('user_id', auth()->id())
                ->value('id');
        }
        $data = app('request')->get();
        if ($id > 0) {
            $res = $scene->update($id, $data);
        } else {
            $res = $scene->insert($data);
        }
        if (!$res) {
            return $this->jsonFailure($scene->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => './'
        ]);
    }

    /**
     * @return ModelModel
     * @throws \Exception
     */
    protected function getModel() {
        if (app('request')->has('id')) {
            return FuncHelper::model(intval(app('request')->get('id')));
        }
        return FuncHelper::model(app('request')->get('model'));
    }
}