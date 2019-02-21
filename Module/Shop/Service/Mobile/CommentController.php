<?php
namespace Module\Shop\Service\Mobile;


use Module\Shop\Domain\Model\OrderGoodsModel;

class CommentController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction($status = 0) {
        $goods_list = OrderGoodsModel::where('user_id', auth()->id())
            ->when($status > 0, function ($query) {
                $query->where('comment_id', '>', 0);
            }, function ($query) {
                $query->where('comment_id', 0);
            })->page();
        return $this->show(compact('goods_list'));
    }


}