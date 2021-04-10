<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Module\Shop\Domain\Repositories\CategoryRepository;

class CategoryController extends Controller {

    public function indexAction(int $id = 0, int $parent = 0) {
        if ($id > 0) {
            return $this->infoAction($id);
        }
        return $this->render(CategoryRepository::getList($parent));
    }

    public function infoAction(int $id) {
        $model = CategoryModel::find($id);
        $data = $model->toArray();
        $extra = request()->get('extra');
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

    public function floorAction() {
        return $this->renderData(CategoryRepository::getHomeFloor());
    }

    public function levelAction() {
        return $this->render(CategoryRepository::levelTree());
    }

    public function treeAction() {
        return $this->render(CategoryRepository::tree());
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