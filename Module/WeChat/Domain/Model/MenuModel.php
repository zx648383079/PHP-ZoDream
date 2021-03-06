<?php
namespace Module\WeChat\Domain\Model;


use Module\WeChat\Domain\EditorInput;
use Zodream\ThirdParty\WeChat\MenuItem;


/**
 * Class MenuModel
 * @package Module\WeChat\Domain\Model
 * @property integer $id
 * @property integer $wid
 * @property string $name
 * @property string $pages
 * @property integer $parent_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class MenuModel extends EditorModel {

    public static function tableName() {
        return 'wechat_menu';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'name' => 'required|string:0,100',
            'type' => 'required|string:0,100',
            'content' => 'required',
            'parent_id' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'wid' => 'Wid',
            'name' => '菜单名',
            'type' => 'Type',
            'content' => 'Content',
            'parent_id' => '上级菜单',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function children() {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    /**
     *
     */
    public function toMenu() {
        $menu = MenuItem::name($this->name);
        if (!empty($this->children)) {
            return $menu->setMenu(array_map(function (MenuModel $model) {
                return $model->toMenu();
            }, $this->children));
        }
        EditorInput::renderMenu($this, $menu);
        return $menu;
    }
}