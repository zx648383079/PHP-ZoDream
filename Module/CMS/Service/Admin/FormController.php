<?php
declare(strict_types=1);
namespace Module\CMS\Service\Admin;

use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Module\CMS\Module;
use Zodream\Infrastructure\Contracts\Http\Output;

class FormController extends Controller {
    public function indexAction(int $id, string $keywords = '') {
        try {
            $model = ModelModel::find($id);
            $scene = CMSRepository::scene()->setModel($model);
            $model_list = $scene->search($keywords, [
                'model_id' => $id
            ], 'id desc');
            return $this->show(compact('model_list', 'keywords', 'model'));
        } catch (\Exception $ex) {
            return $this->redirectWithMessage($this->getUrl(''), '此表单不存在于当前站点');
        }
    }

    public function createAction(int $model_id) {
        return $this->editAction(0, $model_id);
    }

    /**
     * 为了适配可能出现的多表
     * @param int $id
     * @param int $model_id
     * @return Output
     * @throws \Exception
     */
    public function editAction(int $id, int $model_id) {
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = $id > 0 ? $scene->find($id) : [];
        $tab_list = ModelRepository::fieldGroupByTab($model_id);
        return $this->show('edit', compact('id', 'model_id', 'model', 'scene', 'data', 'tab_list'));
    }

    public function saveAction(int $id, int $model_id) {
        $model = ModelModel::find($model_id);
        $scene = CMSRepository::scene()->setModel($model);
        $data = request()->get();
        try {
            if ($id > 0) {
                $scene->update($id, $data);
            } else {
                $scene->insert($data);
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'url' => $this->getUrl('form', ['id' => $model_id])
        ]);
    }

    public function deleteAction(int $id, int $model_id) {
        $model = ModelModel::find($model_id);
        CMSRepository::scene()->setModel($model)->remove($id);
        return $this->renderData([
            'url' => $this->getUrl('form', ['id' => $model_id])
        ]);
    }
}