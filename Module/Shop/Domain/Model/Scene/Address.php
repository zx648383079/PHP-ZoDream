<?php
namespace Module\Shop\Domain\Model\Scene;


use Module\Shop\Domain\Model\AddressModel;

class Address extends AddressModel {

    protected $append = ['region'];
}