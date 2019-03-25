<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Module;

class CategoryController extends Controller {

    public function indexAction($id) {
        $cat = CategoryModel::find($id);

        return $this->show($cat->category_template ?: $cat->model->category_template  ?: null,
            compact('cat'));
    }

    public function listAction($id, $keywords = null) {
        $cat = CategoryModel::find($id);
        $scene = Module::scene()->setModel($cat->model);
        $page = $scene->search($keywords);
        return $this->show($cat->list_template ?: $cat->model->list_template  ?: null,
            compact('cat', 'page'));
    }
}