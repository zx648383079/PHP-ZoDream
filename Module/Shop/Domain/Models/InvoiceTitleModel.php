<?php
namespace Module\Shop\Domain\Models;

use Domain\Model\Model;

class InvoiceTitleModel extends Model {

	public static function tableName() {
        return 'shop_invoice_title';
    }

}