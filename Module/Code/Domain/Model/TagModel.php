<?php
namespace Module\Code\Domain\Model;

use Domain\Model\Model;
/**
 * Class CodeTagsModel
 * @property integer $id
 * @property integer $code_id
 * @property string $content
 */
class TagModel extends Model {

	public static function tableName() {
        return 'code_tags';
    }

    protected function rules() {
		return [
            'code_id' => 'required|int',
            'content' => 'required|string:0,255',
        ];
	}

	protected function labels() {
		return [
            'id' => 'Id',
            'code_id' => 'Code Id',
            'content' => 'Content',
        ];
	}
}