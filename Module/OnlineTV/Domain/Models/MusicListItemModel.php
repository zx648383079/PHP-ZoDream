<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Models;

use Domain\Model\Model;

/**
 *
 * @property integer $id
 * @property integer $list_id
 * @property integer $music_id
 */
class MusicListItemModel extends Model {

	public static function tableName(): string {
        return 'tv_music_list_item';
    }

    protected function rules(): array {
        return [
            'list_id' => 'required|int',
            'music_id' => 'required|int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'list_id' => 'List Id',
            'music_id' => 'Music Id',
        ];
    }

}