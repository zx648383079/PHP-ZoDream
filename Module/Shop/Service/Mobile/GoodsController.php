<?php
namespace Module\Shop\Service\Mobile;

use Module\Shop\Domain\Models\AttributeModel;
use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\AttributeRepository;
use Module\Shop\Domain\Repositories\GoodsRepository;
use Zodream\Html\Page;

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
        try {
            $properties = AttributeRepository::formatPostProperties($properties);
            return $this->renderData(
                GoodsRepository::price($id, $properties, $amount)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
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