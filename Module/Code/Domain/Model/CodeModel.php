<?php
namespace Module\Code\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Code\Domain\Repositories\CodeRepository;


/**
 * Class CodeCodeModel
 * @property integer $id
 * @property integer $user_id
 * @property string $content
 * @property string $language
 * @property integer $recommend_count
 * @property integer $collect_count
 * @property integer $comment_count
 * @property string $source
 * @property integer $created_at
 * @property integer $updated_at
 */
class CodeModel extends Model {

    protected array $append = ['is_recommended', 'tags', 'is_collected'];

	public static function tableName(): string {
        return 'code_code';
    }

    protected function rules(): array {
		return [
            'user_id' => 'int',
            'content' => 'required',
            'language' => 'string:0,20',
            'recommend_count' => 'int',
            'collect_count' => 'int',
            'comment_count' => 'int',
            'source' => 'string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'content' => 'Content',
            'language' => 'Language',
            'recommend_count' => 'Recommend Count',
            'collect_count' => 'Collect Count',
            'comment_count' => 'Comment Count',
            'source' => 'Source',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
	}



    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function tags() {
	    return CodeRepository::tag()->bindRelation('id');
    }

    public function getIsRecommendedAttribute() {
        return CodeRepository::log()->has(CodeRepository::LOG_TYPE_CODE, $this->id,
            CodeRepository::LOG_ACTION_RECOMMEND);
    }

    public function getIsCollectedAttribute() {
        return CodeRepository::log()->has(CodeRepository::LOG_TYPE_CODE, $this->id,
            CodeRepository::LOG_ACTION_COLLECT);
    }
}