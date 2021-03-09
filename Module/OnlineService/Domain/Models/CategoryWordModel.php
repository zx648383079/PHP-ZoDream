<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Models;


use Domain\Model\Model;

/**
 * Class CategoryWordModel
 * @package Module\OnlineService\Domain\Models
 * @property integer $id
 * @property string $content
 * @property integer $cat_id
 */
class CategoryWordModel extends Model {
    public static function tableName() {
        return 'service_category_word';
    }

    protected function rules() {
        return [
            'content' => 'required|string:0,255',
            'cat_id' => 'required|int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'cat_id' => 'Cat Id',
        ];
    }

}