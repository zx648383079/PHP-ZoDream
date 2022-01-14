<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Html\Tree;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\ThirdParty\WeChat\Menu;
use Zodream\ThirdParty\WeChat\MenuItem;

class MenuRepository {

    public static function getList(int $wid) {
        return (new Tree(MenuModel::where('wid', $wid)->orderBy('parent_id', 'asc')->get()))
            ->makeTree();
    }

    public static function get(int $id) {
        return MenuModel::findOrThrow($id, '菜单项错误');
    }

    public static function remove(int $id) {
        MenuModel::where('id', $id)->delete();
    }

    public static function save(int $wid, Input|array $input) {
        $model = new MenuModel();
        $model->load(!is_array($input) ? $input->get() : $input);
        EditorInput::save($model, $input);
        $model->wid = $wid;
        if (!$model->autoIsNew()->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function batchSave(int $wid, array $items) {
        $exist = [];
        foreach ($items as $item) {
            $model = static::save($wid, $item);
            $exist[] = $model->id;
            if (isset($item['children']) && !empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    $child['parent_id'] = $model->id;
                    $m = static::save($wid, $child);
                    $exist[] = $m->id;
                }
            }
        }
        MenuModel::whereNotIn('id', $exist)->where('wid', $wid)->delete();
    }

    public static function async(int $wid) {
        $menu_list = static::getList($wid);
        WeChatModel::find($wid)
            ->sdk(Menu::class)
            ->create(MenuItem::menu(array_map(function (MenuModel $menu) {
                return $menu->toMenu();
            }, $menu_list)));
    }
}