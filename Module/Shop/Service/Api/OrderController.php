<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\OrderModel;

class OrderController extends Controller {

    public function indexAction($id = 0,
                                $status = null,
                                $keywords = null,
                                $per_page = 20, $sort = null, $order = null) {
        if ($id > 0) {
            return $this->render(OrderModel::with('goods')->where('user_id', auth()->id())
                ->where('id', $id)->first());
        }
        $order_list = OrderModel::with('goods')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->page();
        return $this->renderPage($order_list);
    }

    public function countAction() {
        return $this->render([
            'unpay'
        ]);
    }
}