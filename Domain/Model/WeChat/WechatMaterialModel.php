<?php
namespace Domain\Model\WeChat;

use Domain\Model\Model;
/**
* Class WechatMaterialModel
* @property integer $id
* @property integer $wechat_id
* @property string $media_id
* @property string $original_id
* @property integer $parent
* @property string $type
* @property integer $is_mutli
* @property integer $can_edit
* @property string $title
* @property string $descrption
* @property string $author
* @property string $content
* @property string $cover_media_id
* @property string $cover_url
* @property integer $show_cover
* @property integer $create_from
* @property string $source_url
* @property string $content_url
* @property integer $create_at
* @property integer $update_at
*/
class WechatMaterialModel extends Model {
	public static function tableName() {
        return 'wechat_material';
    }

    protected function rules() {
		return array (
		  'wechat_id' => 'int',
		  'media_id' => 'string:3-30',
		  'original_id' => 'string:3-60',
		  'parent' => 'int',
		  'type' => '',
		  'is_mutli' => 'int:0-1',
		  'can_edit' => 'int:0-1',
		  'title' => 'string:3-200',
		  'descrption' => 'string:3-360',
		  'author' => 'string:3-24',
		  'content' => '',
		  'cover_media_id' => 'string:3-100',
		  'cover_url' => 'string:3-100',
		  'show_cover' => 'int:0-1',
		  'create_from' => 'int:0-1',
		  'source_url' => 'string:3-100',
		  'content_url' => 'string:3-100',
		  'create_at' => 'int',
		  'update_at' => 'int',
		);
	}

	protected function labels() {
		return array (
		  'id' => 'Id',
		  'wechat_id' => 'Wechat Id',
		  'media_id' => 'Media Id',
		  'original_id' => 'Original Id',
		  'parent' => 'Parent',
		  'type' => 'Type',
		  'is_mutli' => 'Is Mutli',
		  'can_edit' => 'Can Edit',
		  'title' => 'Title',
		  'descrption' => 'Descrption',
		  'author' => 'Author',
		  'content' => 'Content',
		  'cover_media_id' => 'Cover Media Id',
		  'cover_url' => 'Cover Url',
		  'show_cover' => 'Show Cover',
		  'create_from' => 'Create From',
		  'source_url' => 'Source Url',
		  'content_url' => 'Content Url',
		  'create_at' => 'Create At',
		  'update_at' => 'Update At',
		);
	}
}