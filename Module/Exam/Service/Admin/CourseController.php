<?php
namespace Module\Exam\Service\Admin;

use Module\Exam\Domain\Model\CourseModel;

class CourseController extends Controller {

    public function indexAction() {
        $model_list = CourseModel::tree()->makeTreeForHtml();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = CourseModel::findOrNew($id);
        $cat_list = CourseModel::tree()->makeTreeForHtml();
        if (!empty($id)) {
            $excludes = [$id];
            $cat_list = array_filter($cat_list, function ($item) use (&$excludes) {
                if (in_array($item['id'], $excludes)) {
                    return false;
                }
                if (in_array($item['parent_id'], $excludes)) {
                    $excludes[] = $item['id'];
                    return false;
                }
                return true;
            });
        }
        return $this->show(compact('model', 'cat_list'));
    }

    public function saveAction() {
        $model = new CourseModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('course')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        CourseModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('course')
        ]);
    }
}