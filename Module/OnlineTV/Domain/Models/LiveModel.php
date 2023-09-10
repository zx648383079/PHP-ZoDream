<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property string $title
 * @property string $thumb
 * @property string $source
 * @property integer $status
 * @property integer $updated_at
 * @property integer $created_at
 */
class LiveModel extends Model {

	public static function tableName(): string {
        return 'tv_live';
    }

    protected function rules(): array {
        return [
            'title' => 'required|string:0,255',
            'thumb' => 'string:0,255',
            'source' => 'required|string:0,255',
            'status' => 'int:0,1',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'title' => 'Title',
            'thumb' => 'Thumb',
            'source' => 'Source',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

}