<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

class InvoiceModel extends Model {


	public static function tableName() {
        return 'shop_invoice';
    }

}