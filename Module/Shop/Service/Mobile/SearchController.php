<?php
namespace Module\Shop\Service\Mobile;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\CategoryModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Html\Page;

class SearchController extends Controller {

    public function indexAction($keywords = null, $cat_id = 0, $brand_id = 0) {
        if (empty($keywords) && $cat_id < 1 && $brand_id < 1) {
            return $this->emptyAction();
        }
        /** @var Page $goods_list */
        $goods_list = GoodsModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->when(!empty($cat_id), function ($query) use ($cat_id) {
            $query->whereIn('cat_id', CategoryModel::getChildrenWithParent($cat_id));
        })->when(!empty($brand_id), function ($query) use ($brand_id) {
            $query->where('brand_id', intval($brand_id));
        })->page();
        if (request()->isAjax()) {
            return $this->renderData([
                'html' => $this->renderHtml('page', compact('goods_list', 'keywords')),
                'has_more' => $goods_list->hasMore()
            ]);
        }
        return $this->show(compact('goods_list', 'keywords'));
    }

    public function emptyAction() {
        $history_list = [
            '女装', '童装', '玩具'
        ];
        $hot_list = $history_list;
        return $this->show('empty', compact('history_list', 'hot_list'));
    }
}