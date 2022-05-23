<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property string $name
 */
class AreaModel extends Model {

	public static function tableName() {
        return 'tv_area';
    }

    protected function rules() {
        return [
            'name' => 'required|string:0,30',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'name' => 'Name',
        ];
    }


}