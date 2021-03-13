<?php
namespace Module\Shop\Domain\Models\Activity;


use Domain\Model\Model;

/**
 * Class ActivityTimeModel
 * @package Module\Shop\Domain\Models\Activity
 * @property integer $id
 * @property string $title
 * @property string $start_at
 * @property string $end_at
 */
class ActivityTimeModel extends Model {

    public static function tableName() {
        return 'shop_activity_time';
    }

    public function rules() {
        return [
            'title' => 'required|string:0,40',
            'start_at' => '',
            'end_at' => '',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'title' => '名称',
            'start_at' => 'Start At',
            'end_at' => 'End At',
        ];
    }

    public function getStartAtAttribute() {
        return date('H:i', strtotime($this->getAttributeSource('start_at')));
    }

    public function getEndAtAttribute() {
        return date('H:i', strtotime($this->getAttributeSource('end_at')));
    }

    public static function getTimeList(int $length = 5, string $start_at = '') {
        $model_list = static::query()->orderBy('start_at asc')->all();
        if (empty($model_list)) {
            return [];
        }
        $data = [];
        $is_start = false;
        $now = empty($start_at) ? strtotime(date('H:i')) : strtotime($start_at);
        $next_time = [];
        $next_day = date('Y-m-d ', $now + 86400);
        $today = date('Y-m-d ', $now);
        foreach ($model_list as $item) {
            $item = $item->toArray();
            $item['title'] = $item['start_at'];
            $next_time[] = [
                'title' => $item['start_at'],
                'start_at' => $next_day.$item['start_at'],
                'end_at' => $next_day.$item['end_at'],
            ];
            if (!$is_start) {
                $is_start = strtotime($today.$item['end_at']) > $now;
                if (!$is_start) {
                    continue;
                }
            }
            $data[] = [
                'title' => $item['start_at'],
                'start_at' => $today.$item['start_at'],
                'end_at' => $today.$item['end_at'],
            ];
        }
        $data = array_merge($data, $next_time);
        return array_splice($data, 0, $length);
    }
}