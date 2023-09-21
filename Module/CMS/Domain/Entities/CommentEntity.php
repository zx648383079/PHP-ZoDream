<?php
namespace Module\CMS\Domain\Entities;


use Domain\Concerns\ExtraRule;
use Domain\Entities\Entity;
use Module\CMS\Domain\Repositories\CMSRepository;

/**
 * @property integer $id
 * @property string $content
 * @property string $extra_rule
 * @property integer $parent_id
 * @property integer $position
 * @property integer $reply_count
 * @property integer $user_id
 * @property integer $model_id
 * @property integer $content_id
 * @property integer $agree_count
 * @property integer $disagree_count
 * @property integer $agree_type {0:无，1:同意 2:不同意}
 * @property integer $created_at
 */
class CommentEntity extends Entity {

    use ExtraRule;

    public static function tableName(): string {
        return 'cms_comment_'.CMSRepository::siteId();
    }

    protected function rules(): array {
        return [
            'content' => 'required|string:0,255',
            $this->extraRuleKey => '',
            'parent_id' => 'int',
            'position' => 'int',
            'user_id' => 'required|int',
            'model_id' => 'required|int',
            'content_id' => 'required|int',
            'reply_count' => 'int',
            'agree_count' => 'int',
            'disagree_count' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'content' => 'Content',
            'parent_id' => 'Parent Id',
            'position' => 'Position',
            'user_id' => 'User Id',
            'model_id' => 'Model Id',
            'content_id' => 'Content Id',
            'agree_count' => 'Agree Count',
            'disagree_count' => 'Disagree Count',
            'created_at' => 'Created At',
        ];
    }
}