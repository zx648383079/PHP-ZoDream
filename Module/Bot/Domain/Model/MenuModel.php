<?php
namespace Module\Bot\Domain\Model;


use Module\Bot\Domain\EditorInput;
use Zodream\ThirdParty\WeChat\MenuItem;


/**
 * Class MenuModel
 * @package Module\Bot\Domain\Model
 * @property integer $id
 * @property integer $bot_id
 * @property string $name
 * @property integer $type
 * @property string $content
 * @property integer $parent_id
 * @property integer $updated_at
 * @property integer $created_at
 */
class MenuModel extends EditorModel {

    public static function tableName(): string {
        return 'bot_menu';
    }

    protected function rules(): array {
        return [
            'bot_id' => 'required|int',
            'name' => 'required|string:0,100',
            'type' => 'int:0,127',
            'content' => 'string:0,500',
            'parent_id' => 'int',
            'updated_at' => 'int',
            'created_at' => 'int',
        ];
    }

    protected function labels(): array {
        return [
            'id' => 'Id',
            'bot_id' => 'bot_id',
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