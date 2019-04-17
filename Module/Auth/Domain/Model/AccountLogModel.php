<?php
namespace Module\Auth\Domain\Model;


use Domain\Model\Model;

/**
 * Class AccountLogModel
 * @package Module\Auth\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property integer $item_id
 * @property integer $money
 * @property integer $status
 * @property string $remark
 * @property integer $created_at
 * @property integer $updated_at
 */
class AccountLogModel extends Model {

    const TYPE_DEFAULT = 99;
    const TYPE_CHECK_IN = 30;
    const TYPE_GAME = 40;

    public static function tableName() {
        return 'account_log';
    }

    protected function rules() {
        return [
            'user_id' => 'int',
            'type' => 'int:0,99',
            'item_id' => 'int',
            'money' => 'required|int',
            'status' => 'int:0,9',
            'remark' => 'required|string:0,255',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'type' => 'Type',
            'item_id' => 'Item Id',
            'money' => 'Money',
            'status' => 'Status',
            'remark' => 'Remark',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function log($user_id, $type, $item_id, $money, $remark, $status = 0) {

        return static::create(
            compact('user_id', 'type', 'item_id', 'money', 'remark', 'status'));
    }

    public static function change($user_id, $type, $item_id, $money, $remark, $status = 0) {
        if (empty($user_id)) {
            $user_id = auth()->id();
        }
        $old_money = UserModel::query()->where('user_id', $user_id)
            ->value('money');
        $new_money = floatval($old_money) + $money;
        if ($new_money < 0) {
            return false;
        }
        UserModel::query()->where('user_id', $user_id)->update([
            'money' => $new_money
        ]);
        static::log($user_id, $type, $item_id, $money, $remark, $status);
        if (auth()->id() === $user_id) {
            // 自动更新当前用户信息
            auth()->user()->moeny = $new_money;
        }
        return true;
    }

    public static function isBought($id, $type = self::TYPE_DEFAULT) {
        return self::where('item_id', $id)
                ->where('type', $type)->count() > 0;
    }


}