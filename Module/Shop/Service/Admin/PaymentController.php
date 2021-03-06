<?php
namespace Module\Shop\Service\Admin;

use Module\OpenPlatform\Domain\Concerns\EditPlatformOption;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Models\ShippingModel;
use Module\Shop\Domain\Repositories\PaymentRepository;

class PaymentController extends Controller {

    use EditPlatformOption;

    public function indexAction() {
        $model_list = PaymentModel::page();
        return $this->show(compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = PaymentModel::findOrNew($id);
        $pay_list = PaymentRepository::getPlugins();
        $shipping_list = ShippingModel::select('id', 'name')->all();
        return $this->show('edit', compact('model', 'pay_list', 'shipping_list'));
    }

    public function saveAction() {
        $model = new PaymentModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->renderData([
                'url' => $this->getUrl('payment')
            ]);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        PaymentModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('payment')
        ]);
    }

    protected function platformOption() {
        return [
            'wechat_pay' => [
                '_label' => '微信支付',
                'appid' => '',
                'secret' => '',
                'mch_id' => '',
                'mch_name' => '',
                'key' => '',
                'key_label' => 'API KEY',
                'mch_id_label' => '商户号',
                'mch_name_label' => '商户名称',
            ],
        ];
    }
}