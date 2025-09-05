<?php
namespace Module\Counter\Domain\Model;

use Domain\Model\Model;

/**
 * @property integer $id
 * @property string $name
 */
class PathnameModel extends Model
{
    public static function tableName(): string
    {
        return 'ctr_pathname';
    }

    protected function rules(): array {
        return [
            'name' => 'required|string:0,255',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'name' => 'Name',
        ];
    }
}