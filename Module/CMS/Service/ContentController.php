<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Module;

class ContentController extends Controller {

    public function indexAction($id, $category) {
        FuncHelper::$current['channel'] = intval($category);
        FuncHelper::$current['content'] = intval($id);
        $cat = FuncHelper::channel($category, true);
        if (empty($cat)) {
            return $this->redirect('./');
        }
        $scene = Module::scene()->setModel($cat->model);
        $data = $scene->find($id);
        if (empty($data)) {
            return $this->redirect('./');
        }
        $data['view_count'] ++;
        $scene->update($id, ['view_count' => $data['view_count']]);
        $title = $data['title'];
        return $this->show($cat->show_template,
            compact('cat', 'data', 'title'));
    }
}