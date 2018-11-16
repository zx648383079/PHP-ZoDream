<?php
namespace Module\Shop\Service;

use Module\Shop\Domain\Model\GoodsGalleryModel;
use Module\Shop\Domain\Model\GoodsModel;

class GoodsController extends Controller {

    public function indexAction($id) {
        $goods = GoodsModel::find($id);
        $hot_goods = GoodsModel::limit(7)->select(GoodsModel::THUMB_MODE)->all();
        $gallery_list = GoodsGalleryModel::where('goods_id', $id)->all();
        return $this->sendWithShare()->show(compact('goods', 'hot_goods', 'gallery_list'));
    }
}