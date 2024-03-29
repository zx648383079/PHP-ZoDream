<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class ActionLogModel
 * @property integer $id
 * @property string $ip
 * @property integer $user_id
 * @property string $action
 * @property string $remark
 * @property integer $created_at
 */
class ActionLogModel extends Model {

	public static function tableName(): string {
        return 'user_action_log';
    }

    protected function rules(): array {
        return [
            'ip' => 'required|string:0,120',
            'user_id' => 'required|int',
            'action' => 'required|string:0,30',
            'remark' => 'string:0,255',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'ip' => 'Ip',
            'user_id' => 'User Id',
            'action' => 'Action',
            'remark' => 'Remark',
            'created_at' => 'Created At',
        ];
    }

    public function user() {
        return $this->hasOne(UserSimpleModel::class, 'id', 'user_id');
    }


    /**
     * 用户操作记录
     * @param int $user_id
     * @param $action
     * @param string $remark
     * @return ActionLogModel
     * @throws \Exception
     */
	public static function addLog($user_id, $action, $remark = '') {
		return static::create([
            'ip' => request()->ip(),
            'user_id' => $user_id,
            'action' => $action,
            'remark' => $remark,
            'created_at' => time()
        ]);
	}
}