<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Model\OrderModel;

class OrderController extends Controller {

    public function indexAction($id = 0,
                                $status = null,
                                $keywords = null,
                                $per_page = 20, $sort = null, $order = null) {
        if ($id > 0) {
            return $this->infoAction($id);
        }
        $order_list = OrderModel::with('goods')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->page();
        return $this->renderPage($order_list);
    }

    public function infoAction($id) {
        return $this->render(OrderModel::with('goods')->where('user_id', auth()->id())
            ->where('id', $id)->first());
    }

    public function countAction() {
        $data = OrderModel::where('user_id', auth()->id())->groupBy('status')->asArray()
            ->get('status, COUNT(*) AS count');
        return $this->render([
            'unpay' => 0,
            'unshipping' => 0,
            'unconfirm' => 0,
            'uncomment' => 0,
            'finish' => 0
        ]);
    }
}