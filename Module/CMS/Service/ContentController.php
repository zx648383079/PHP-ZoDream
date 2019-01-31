<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Module;

class ContentController extends Controller {

    public function indexAction($id, $cat_id) {
        $cat = CategoryModel::find($cat_id);
        $scene = Module::scene()->setModel($cat->model);
        $data = $scene->find($id);
        return $this->show($cat->show_template ?: $cat->show_template ?: null,
            compact('cat', 'data'));
    }
}