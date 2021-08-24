<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $name
 */
class TagModel extends Model {
    public static function tableName() {
        return 'search_tag';
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
