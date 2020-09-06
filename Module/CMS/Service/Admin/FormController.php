<?php
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Module;

class FormController extends Controller {
    public function indexAction($id, $keywords = null) {
        $model = ModelModel::find($id);
        $scene = CMSRepository::scene()->setModel($model);
        $model_list = $scene->search($keywords, [
            'model_id' => $id
        ], 'id desc');
        return $this->show(compact('model_list', 'keywords', 'model'));
    }

    public function createAction($model_id) {
        return $this->runMethodNotProcess('edit', ['id' => null, 'model_id' => $model_id]);
    }

    /**
     * 为了适配可能出现的多表
     * @param $id
     * @param $model_id
     * @return \Zodream\Infrastructure\Http\Response
     * @throws \Exception
     */
    public function editAction($id, $model_id) {
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = $id > 0 ? $scene->find($id) : [];
        $tab_list = ModelFieldModel::tabGroups($model_id);
        return $this->show(compact('id', 'model_id', 'model', 'scene', 'data', 'tab_list'));
    }

    public function saveAction($id, $model_id) {
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = app('request')->get();
        if ($id > 0) {
            $scene->update($id, $data);
        } else {
            $scene->insert($data);
        }
        if ($scene->hasError()) {
            return $this->jsonFailure($scene->getFirstError());
        }
        return $this->jsonSuccess([
            'url' => $this->getUrl('form', ['id' => $model_id])
        ]);
    }

    public function deleteAction($id, $model_id) {
        $model = ModelModel::find($model_id);
        CMSRepository::scene()->setModel($model)->remove($id);
        return $this->jsonSuccess([
            'url' => $this->getUrl('form', ['id' => $model_id])
        ]);
    }
}