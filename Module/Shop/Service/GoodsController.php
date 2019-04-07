<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Models\CommentModel;
use Module\Shop\Domain\Models\GoodsGalleryModel;
use Module\Shop\Domain\Models\GoodsIssueModel;
use Module\Shop\Domain\Models\GoodsModel;

class GoodsController extends Controller {

    public function indexAction($id) {
        if (app('request')->isMobile()) {
            return $this->redirect(['./mobile/goods', 'id' => $id]);
        }
        $goods = GoodsModel::find($id);
        $hot_goods = GoodsModel::limit(7)->select(GoodsModel::THUMB_MODE)->all();
        $gallery_list = GoodsGalleryModel::where('goods_id', $id)->all();
        $issue_list = GoodsIssueModel::whereIn('goods_id', [$id, 0])->all();
        $comment_list = CommentModel::with('images', 'user')->where('item_type', 0)->where('item_id', $id)->all();
        return $this->sendWithShare()->show(compact('goods', 'hot_goods', 'gallery_list', 'issue_list', 'comment_list'));
    }
}