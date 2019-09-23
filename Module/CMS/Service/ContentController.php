<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Module;

class ContentController extends Controller {

    public function indexAction($id, $category, $model) {
        $cat = FuncHelper::channel($category, true);
        if (empty($cat)) {
            return $this->redirect('./');
        }
        FuncHelper::$current['channel'] = $cat->id;
        FuncHelper::$current['content'] = intval($id);
        $model = FuncHelper::model($model);
        if (empty($model)) {
            return $this->redirect('./');
        }
        FuncHelper::$current['model'] = $model->id;
        $scene = Module::scene()->setModel($model);
        $data = $scene->find($id);
        if (empty($data)) {
            return $this->redirect('./');
        }
        $data['view_count'] ++;
        $scene->update($id, ['view_count' => $data['view_count']]);
        $title = $data['title'];
        return $this->show(
            $cat->model_id === $model->id
                ? $cat->show_template
                : $model->show_template,
            compact('cat', 'data', 'title'));
    }
}