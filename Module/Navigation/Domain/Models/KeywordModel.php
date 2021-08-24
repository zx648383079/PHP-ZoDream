<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $word
 * @property integer $type
 */
class KeywordModel extends Model {
    public static function tableName() {
        return 'search_keyword';
    }

    protected function rules() {
        return [
            'word' => 'required|string:0,30',
            'type' => 'int:0,127',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'word' => 'Word',
            'type' => 'Type',
        ];
    }
}
