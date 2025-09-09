<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * Class PageLogModel
 * @package Module\Counter\Domain\Model
 * @property integer $id
 * @property integer $host_id
 * @property integer $path_id
 * @property integer $visit_count
 */
class PageLogModel extends Model {

    public bool $timestamps = false;

    public static function tableName(): string {
        return 'ctr_page_log';
    }

    protected function rules(): array {
        return [
            'host_id' => 'required|int',
            'path_id' => 'required|int',
            'visit_count' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'host_id' => 'Host Id',
            'path_id' => 'Path Id',
            'visit_count' => 'Visit Count',
        ];
    }

}
