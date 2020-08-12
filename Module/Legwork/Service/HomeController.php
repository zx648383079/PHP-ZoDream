<?php
namespace Module\Legwork\Service;

use Module\Legwork\Domain\Model\CategoryModel;
use Module\Legwork\Domain\Model\ServiceModel;

class HomeController extends Controller {

    public function indexAction($category = 0, $keywords = '') {
        $cat_list = CategoryModel::query()->get();
        $model_list = ServiceModel::with('category')->when(!empty($keywords), function ($query) {
            ServiceModel::searchWhere($query, 'name');
        })->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->orderBy('id', 'desc')->page();
        return $this->show(compact('cat_list', 'category', 'keywords', 'model_list'));
    }

    public function suggestionAction($keywords) {
        $data = ServiceModel::when(!empty($keywords), function ($query) {
            ServiceModel::searchWhere($query, 'name');
        })->limit(4)->pluck('name');
        return $this->jsonSuccess($data);
    }
}