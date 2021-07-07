<?php
namespace Module\Shop\Service\Api\Admin;

use Module\OpenPlatform\Domain\Concerns\ApiPlatformOption;
use Module\Shop\Domain\Models\PaymentModel;
use Module\Shop\Domain\Repositories\PaymentRepository;

class PaymentController extends Controller {

    use ApiPlatformOption;

    public function indexAction() {
        $model_list = PaymentModel::page();
        return $this->renderPage($model_list);
    }

    public function detailAction(int $id) {
        $model = PaymentModel::find($id);
        return $this->render($model);
    }

    public function pluginAction() {
        $items = PaymentRepository::getPlugins();
        $data = [];
        foreach ($items as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $this->renderData($data);
    }

    public function saveAction() {
        $model = new PaymentModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->render($model);
        }
        return $this->renderFailure($model->getFirstError());
    }

    public function deleteAction(int $id) {
        PaymentModel::where('id', $id)->delete();
        return $this->renderData(true);
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