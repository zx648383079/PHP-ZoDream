<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Module;

class ContentController extends Controller {

    public function indexAction($id, $category) {
        $cat = CategoryModel::find($category);
        $scene = Module::scene()->setModel($cat->model);
        $data = $scene->find($id);
        return $this->show($cat->show_template,
            compact('cat', 'data'));
    }
}