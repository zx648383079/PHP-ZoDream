<?php
namespace Module\Shop\Service\Api;


use Module\Shop\Domain\Models\InvoiceModel;
use Module\Shop\Domain\Models\InvoiceTitleModel;
use Module\Shop\Domain\Models\OrderModel;

class InvoiceController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        $order_list = OrderModel::where('user_id', auth()->id())
            ->whereIn('status', [OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH])
            ->where('invoice_id', 0)->get();
        return $this->render($order_list);
    }

    public function titleAction($id = 0) {
        if (empty($id)) {
            $model_list = InvoiceTitleModel::where('user_id', auth()->id())->page();
            return $this->renderPage($model_list);
        }
        $model = InvoiceTitleModel::findWithAuth($id);
        return $this->render($model);
    }

    public function saveTitleAction() {
        $model = new InvoiceTitleModel();
        $model->user_id = auth()->id();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function logAction() {
        $model_list = InvoiceModel::where('user_id', auth()->id())->orderBy('id', 'desc')->page();
        return $this->renderPage($model_list);
    }

    public function applyAction() {

    }

    public function subtotalAction() {
        return $this->render([
            'remain' => OrderModel::where('user_id', auth()->id())
                ->whereIn('status', [OrderModel::STATUS_RECEIVED, OrderModel::STATUS_FINISH])
                ->where('invoice_id', 0)->sum('goods_amount')
        ]);
    }
}