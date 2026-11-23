<?php
namespace Module\Legwork\Service;

use Domain\Model\SearchModel;
use Module\Legwork\Domain\Model\CategoryModel;
use Module\Legwork\Domain\Model\ServiceModel;

class HomeController extends Controller {

    public function indexAction(int $category = 0, string $keywords = '') {
        $cat_list = CategoryModel::query()->get();
        $model_list = ServiceModel::with('category')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->orderBy('id', SORT_DESC)->page();
        return $this->show(compact('cat_list', 'category', 'keywords', 'model_list'));
    }

    public function suggestAction(string $keywords) {
        $data = ServiceModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->take(4)->pluck('name');
        return $this->renderData($data);
    }
}