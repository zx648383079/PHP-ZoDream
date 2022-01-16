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
        AccountRepository::isSelf($wid);
        return (new Tree(MenuModel::where('wid', $wid)->orderBy('parent_id', 'asc')->get()))
            ->makeTree();
    }

    public static function get(int $id) {
        return MenuModel::findOrThrow($id, '菜单项错误');
    }

    public static function getSelf(int $id) {
        $model = static::get($id);
        AccountRepository::isSelf($model->wid);
        return $model;
    }

    public static function remove(int $id) {
        $model = MenuModel::find($id);
        AccountRepository::isSelf($model->wid);
        $model->delete();
    }

    public static function save(int $wid, array $input) {
        $id = $input['id'] ?? 0;
        unset($input['id'], $input['wid']);
        if ($id > 0) {
            $model = static::getSelf($id);
            if ($model->wid != $wid) {
                throw new \Exception('不同公众号的菜单不能共用');
            }
        } else {
            $model = new MenuModel();
            $model->wid = $wid;
            AccountRepository::isSelf($model->wid);
        }
        $model->load($input);
        EditorInput::save($model, $input);
        if (!$model->save()) {
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
        AccountRepository::isSelf($wid);
        $menu_list = static::getList($wid);
        WeChatModel::find($wid)
            ->sdk(Menu::class)
            ->create(MenuItem::menu(array_map(function (MenuModel $menu) {
                return $menu->toMenu();
            }, $menu_list)));
    }
}