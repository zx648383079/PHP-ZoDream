<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Model;

use Domain\Model\Model;

/**
 * Class BlackWordModel
 * @package Module\Forum\Domain\Model
 * @property integer $id
 * @property string $words
 * @property string $replace_words
 */
class BlackWordModel extends Model {

    public $timestamps = false;

    public static function tableName() {
        return 'seo_black_word';
    }

    protected function rules() {
        return [
            'words' => 'required|string:0,255',
            'replace_words' => 'string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'words' => 'Words',
            'replace_words' => 'Replace Words',
        ];
    }
}