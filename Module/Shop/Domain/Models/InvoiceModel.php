<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

class InvoiceModel extends Model {

	public static function tableName() {
        return 'shop_invoice';
    }

}