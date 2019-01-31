<?php
namespace Module\CMS\Service;

use Module\CMS\Domain\Model\CategoryModel;

class CategoryController extends Controller {

    public function indexAction($id) {
        $cat = CategoryModel::find($id);
        return $this->show($cat->category_template ?: $cat->model->category_template  ?: null,
            compact('cat'));
    }
}