<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;
use Module\Counter\Domain\Events\CounterState;

/**
 * Class PageLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property string $url
 * @property integer $visit_count
 */
class PageLogModel extends Model {

    public $timestamps = false;

    public static function tableName(): string {
        return 'ctr_page_log';
    }

    protected function rules(): array {
        return [
            'url' => 'required|string:0,255',
            'visit_count' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'url' => 'Url',
            'visit_count' => 'Visit Count',
        ];
    }

    public static function log(CounterState $state) {
        if ($state->status !== CounterState::STATUS_ENTER) {
            return;
        }
        $url = $state->url;
        $model = static::where('url', $url)->first();
        if ($model) {
            $model->visit_count ++;
            $model->save();
            return;
        }
        static::create([
            'url' => $url,
            'visit_count' => 1
        ]);
    }
}
