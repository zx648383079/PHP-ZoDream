<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;

class CategoryController extends Controller {

    public function indexAction($id = 0, $parent = 0) {
        if ($id > 0) {
            return $this->infoAction($id);
        }
        return $this->render(CategoryModel::where('parent_id', intval($parent))->all());
    }

    public function infoAction($id) {
        $model = CategoryModel::find(intval($id));
        $data = $model->toArray();
        $extra = app('request')->get('extra');
        if (!empty($extra)) {
            $extra = explode(',', $extra);
            if (in_array('goods_list', $extra)) {
                $data['goods_list'] = GoodsSimpleModel::whereIn('cat_id', $model->getFamily())
                    ->where('is_best', 1)->all();
            }
            if (in_array('children', $extra)) {
                $data['children'] = $this->formatCategory(CategoryModel::getChildrenItem($id));
            }
        }
        return $this->render($data);
    }

    public function levelAction() {
        return $this->render(CategoryModel::cacheLevel());
    }

    public function treeAction() {
        return $this->render(CategoryModel::cacheTree());
    }

    private function formatCategory(array $data) {
        if (empty($data)) {
            return $data;
        }
        return array_map(function ($item) {
            if (isset($item['children'])) {
                $item['children'] = $this->formatCategory($item['children']);
            }
            return $item;
        }, array_values($data));
    }
}