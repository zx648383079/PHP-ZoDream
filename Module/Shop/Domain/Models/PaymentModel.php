<?php
namespace Module\Shop\Domain\Models;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/12/15
 * Time: 19:07
 */
use Domain\Model\Model;

/**
 * Class PaymentModel
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property string $icon
 * @property string $description
 * @property string $settings
 */
class PaymentModel extends Model {

    const COD_CODE = 'cod';

    public static function tableName(): string {
        return 'shop_payment';
    }

    public function rules(): array {
        return [
            'name' => 'required|string:0,30',
            'code' => 'required|string:0,30',
            'icon' => 'string:0,100',
            'description' => 'string:0,255',
            'settings' => '',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => '名称',
            'code' => 'Code',
            'icon' => '图标',
            'description' => '介绍',
            'settings' => '配置',
        ];
    }

    public function getIconAttribute() {
        $thumb = $this->getAttributeSource('icon');
        if (empty($thumb)) {
            return '';
        }
        return url()->asset($thumb);
    }

}