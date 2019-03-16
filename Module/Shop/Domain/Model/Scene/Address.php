<?php
namespace Module\Shop\Domain\Model\Scene;


use Module\Shop\Domain\Model\AddressModel;
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

    protected $append = ['region', 'is_default'];
}