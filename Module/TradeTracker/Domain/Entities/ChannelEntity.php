<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Entities;

use Domain\Entities\Entity;
/**
 * 
 * @property integer $id
 * @property string $short_name
 * @property string $name
 * @property string $site_url
 * @property integer $updated_at
 * @property integer $created_at
 */
class ChannelEntity extends Entity {

    public static function tableName(): string {
        return 'tt_channels';
    }

    protected function rules(): array {
		return [
            'short_name' => 'string:0,20',
            'name' => 'required|string:0,40',
            'site_url' => 'string:0,255',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
	}

	protected function labels(): array {
		return [
            'id' => 'Id',
            'short_name' => 'Short Name',
            'name' => 'Name',
            'site_url' => 'Site Url',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
	}
}