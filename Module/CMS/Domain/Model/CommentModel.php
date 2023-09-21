<?php
namespace Module\CMS\Domain\Model;

use Domain\Concerns\ExtraRule;
use Module\CMS\Domain\Entities\CommentEntity;
use Module\CMS\Domain\Repositories\CMSRepository;

/**
 * Class CommentModel
 * @package Module\CMS\Domain\Model
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
class CommentModel extends CommentEntity {

}