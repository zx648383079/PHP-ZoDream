<?php
namespace Module\Auth\Domain\Model\Bulletin;

use Domain\Model\Model;
use Infrastructure\LinkRule;
use Module\Auth\Domain\Model\UserSimpleModel;
use Zodream\Helpers\Json;


/**
 * Class BulletinModel
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $extra_rule
 * @property integer $type
 * @property integer $user_id
 * @property integer $create_at
 * @property integer $delete_at
 * @property UserSimpleModel $user
 */
class BulletinModel extends Model {

    protected array $append = ['user', 'user_name', 'icon'];

	public static function tableName() {
        return 'bulletin';
    }

    protected function rules() {
        return [
            'title' => 'required|string:0,100',
            'content' => 'required|string:0,255',
            'extra_rule' => '',
            'type' => 'int:0,99',
            'user_id' => 'required|int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'content' => 'Content',
            'extra_rule' => '额外规则',
            'type' => 'Type',
            'user_id' => 'User Id',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function user() {
	    return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }

    public function getExtraRuleAttribute() {
        $value = $this->getAttributeValue('extra_rule');
        return empty($value) ? [] : Json::decode($value);
    }

    public function setExtraRuleAttribute($value) {
        $this->__attributes['extra_rule'] = is_array($value) ? Json::encode($value) : $value;
    }

    public function getUserNameAttribute() {
	    if ($this->user_id < 1) {
	        return '[系统通知]';
        }
	    if ($this->user) {
            return $this->user->name;
        }
        return '[用户已删除]';
    }

    public function getIconAttribute() {
        if ($this->user_id < 1) {
            return '系';
        }
        if ($this->user) {
            return mb_substr($this->user->name, 0, 1);
        }
        return 'NULL';
    }

    public function getHtmlAttribute() {
        return LinkRule::render($this->content, $this->extra_rule);
    }
}