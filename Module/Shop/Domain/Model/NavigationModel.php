<?php
namespace Module\Shop\Domain\Model;

use Domain\Model\Model;

/**
 * Class NavigationModel
 * @property integer $id
 * @property string $type
 * @property string $name
 * @property string $url
 * @property string $target
 * @property integer $visible
 * @property integer $position
 */
class NavigationModel extends Model {

    public static $type_list = [
        'middle' => '主导航栏',
        'top' => '顶部导航栏',
        'bottom' => '底部导航栏',
    ];

    public static function tableName() {
        return 'navigation';
    }


    protected function rules() {
        return [
            'type' => 'string:0,10',
            'name' => 'required|string:0,100',
            'url' => 'string:0,200',
            'target' => 'string:0,10',
            'visible' => 'int:0,9',
            'position' => 'int:0,999',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'type' => 'Type',
            'name' => 'Name',
            'url' => 'Url',
            'target' => 'Target',
            'visible' => 'Visible',
            'position' => 'Position',
        ];
    }

    public static function getByType() {
        $model_list = static::where('visible', 1)->all();
        $data = [
            'middle' => [],
            'top' => [],
            'bottom' => []
        ];
        foreach ($model_list as $item) {
            $data[$item->type][] = $item;
        }
        return $data;
    }

}