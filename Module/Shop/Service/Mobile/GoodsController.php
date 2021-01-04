<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\GoodsRepository;

class GoodsController extends Controller {

    public function indexAction($id) {
        $goods = GoodsModel::find($id);
        if (empty($goods)) {
            return $this->redirect($this->getUrl(''));
        }
        $goods_list = GoodsModel::where('is_best', 1)->limit(3)->all();
        $comment_list = CommentModel::with('images', 'user')->where('item_type', 0)
            ->where('item_id', $id)->limit(3)->all();
        return $this->show(compact('goods', 'goods_list', 'comment_list'));
    }

    public function priceAction($id, $amount = 1, $properties = null) {
        $goods = GoodsModel::where('id', $id)->first('id', 'price', 'stock');
        $price = GoodsRepository::finalPrice($goods, $amount, $properties);
        $box = AttributeModel::getProductAndPriceWithProperties($properties, $id);
        return $this->renderData([
            'price' => $price,
            'total' => $price * $amount,
            'stock' => !empty($box['product']) ? $box['product']->stock : $goods->stock
        ]);
    }

    public function commentAction($id) {
        /** @var Page $goods_list */
        $comment_list = CommentModel::with('images', 'user')->where('item_type', 0)
            ->where('item_id', $id)->page();
        if (request()->isAjax()) {
            return $this->renderData([
                'html' => $this->renderHtml('page', compact('comment_list', 'id')),
                'has_more' => $goods_list->hasMore()
            ]);
        }
        return $this->show(compact('comment_list', 'id'));
    }
}