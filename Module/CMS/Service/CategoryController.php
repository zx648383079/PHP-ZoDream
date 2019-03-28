<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Module;

class CategoryController extends Controller {

    public function indexAction($id) {
        $cat = CategoryModel::find($id);
        $page = null;
        if ($cat->type < 1) {
            $scene = Module::scene()->setModel($cat->model);
            $page = $scene->search(null, $id);
        }
        return $this->show($cat->category_template,
            compact('cat', 'page'));
    }

    public function listAction($id, $keywords = null) {
        $cat = CategoryModel::find($id);
        $scene = Module::scene()->setModel($cat->model);
        $page = $scene->search($keywords, $id);
        return $this->show($cat->list_template,
            compact('cat', 'page'));
    }
}