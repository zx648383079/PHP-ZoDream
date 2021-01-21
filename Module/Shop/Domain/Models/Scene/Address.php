<?php
namespace Module\Shop\Domain\Models\Scene;


use Module\Shop\Domain\Models\AddressModel;
/**
 * Class AddressModel
 * @property integer $id
 * @property string $name
 * @property integer $region_id
 * @property integer $user_id
 * @property string $tel
 * @property string $address
 * @property integer $created_at
 * @property integer $updated_at
 */
class Address extends AddressModel {

    protected array $append = ['region', 'is_default'];

    public function getTelAttribute() {
        return $this->hide_tel;
    }
}