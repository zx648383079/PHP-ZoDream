<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Zodream\Infrastructure\Http\Request;

/**
 * Class VisitorLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $user_id
 * @property string $ip
 * @property integer $first_at
 * @property integer $last_at
 */
class VisitorLogModel extends Model {

    public static function tableName() {
        return 'ctr_visitor_log';
    }

    protected function rules() {
        return [
            'user_id' => 'string:0,50',
            'ip' => 'required|string:0,120',
            'first_at' => 'int',
            'last_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'ip' => 'Ip',
            'first_at' => 'First At',
            'last_at' => 'Last At',
        ];
    }

    public static function log(Request $request) {
        if ($request->has('loaded') || $request->has('leave')) {
            return;
        }
        $ip = $request->ip();
        $user_id = auth()->id();
        $model = static::where('ip', $ip)->where('user_id', $user_id)->first();
        if ($model) {
            $model->last_at = time();
            $model->save();
            return;
        }
        static::create([
            'ip' => $ip,
            'user_id' => $user_id,
            'first_at' => time(),
            'last_at' => time(),
        ]);
    }
}
