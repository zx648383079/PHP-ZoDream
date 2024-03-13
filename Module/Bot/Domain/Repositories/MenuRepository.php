<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Module\Bot\Domain\EditorInput;
use Module\Bot\Domain\Model\MenuModel;
use Zodream\Html\Tree;

class MenuRepository {

    public static function getList(int $bot_id) {
        return (new Tree(MenuModel::where('bot_id', $bot_id)->orderBy('parent_id', 'asc')->get()))
            ->makeTree();
    }

    public static function getManageList(int $bot_id) {
        AccountRepository::isSelf($bot_id);
        return (new Tree(MenuModel::where('bot_id', $bot_id)->orderBy('parent_id', 'asc')->get()))
            ->makeTree();
    }

    public static function manageList(int $bot_id = 0) {
        return MenuModel::when($bot_id > 0, function ($query) use ($bot_id) {
            $query->where('bot_id', $bot_id);
        })->page();
    }

    public static function get(int $id) {
        return MenuModel::findOrThrow($id, '菜单项错误');
    }

    public static function getSelf(int $id) {
        $model = static::get($id);
        AccountRepository::isSelf($model->bot_id);
        return $model;
    }

    public static function remove(int $id) {
        $model = MenuModel::find($id);
        AccountRepository::isSelf($model->bot_id);
        $model->delete();
    }

    public static function save(int $bot_id, array $input) {
        $id = $input['id'] ?? 0;
        unset($input['id'], $input['bot_id']);
        if ($id > 0) {
            $model = static::getSelf($id);
            if ($model->bot_id != $bot_id) {
                throw new \Exception('不同公众号的菜单不能共用');
            }
        } else {
            $model = new MenuModel();
            $model->bot_id = $bot_id;
            AccountRepository::isSelf($model->bot_id);
        }
        $model->load($input);
        EditorInput::save($model, $input);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function batchSave(int $bot_id, array $items) {
        $exist = [];
        foreach ($items as $item) {
            $model = static::save($bot_id, $item);
            $exist[] = $model->id;
            if (isset($item['children']) && !empty($item['children'])) {
                foreach ($item['children'] as $child) {
                    $child['parent_id'] = $model->id;
                    $m = static::save($bot_id, $child);
                    $exist[] = $m->id;
                }
            }
        }
        MenuModel::whereNotIn('id', $exist)->where('bot_id', $bot_id)->delete();
    }

    public static function async(int $bot_id) {
        AccountRepository::isSelf($bot_id);
        BotRepository::entry($bot_id)->pushMenu(
            self::getList($bot_id)
        );
    }
}