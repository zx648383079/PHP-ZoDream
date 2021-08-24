<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Models;

use Domain\Model\Model;

/**
 * @property integer $page_id
 * @property integer $word_id
 * @property bool $is_official
 */
class PageKeywordModel extends Model {
    protected $primaryKey = '';

    public static function tableName() {
        return 'search_page_keyword';
    }

    protected function rules() {
        return [
            'page_id' => 'required|int',
            'word_id' => 'required|int',
            'is_official' => 'int:0,1',
        ];
    }

    protected function labels() {
        return [
            'page_id' => 'Page Id',
            'word_id' => 'Word Id',
            'is_official' => 'is official',
        ];
    }


}
