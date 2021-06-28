<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Book\Domain\Model\BookRoleModel;
use Module\Book\Domain\Model\RoleRelationModel;

class RoleRepository {

    public static function getList(int $book, string $keywords = '') {
        return BookRoleModel::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'character']);
            })->where('book_id', $book)->page();
    }

    public static function all(int $book) {
        $items = BookRoleModel::where('book_id', $book)->get();
        $link_items = [];
        if (empty($items)) {
            return compact('items', 'link_items');
        }
        $idItems = [];
        foreach ($items as $item) {
            $idItems[] = $item['id'];
        }
        $link_items = RoleRelationModel::whereIn('role_id', $idItems)->get();
        return compact('items', 'link_items');
    }


    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = BookRoleModel::findOrNew($id);
        $model->load($data);
        if (!$model->save() && $id < 1) {
            throw new \Exception($model->getFirstError());
        }
        static::addLink($model->id, $data['link_id'] ?? 0, $data['link_title'] ?? '');
        return $model;
    }

    public static function remove(int $id) {
        $model = BookRoleModel::find($id);
        if (empty($model)) {
            throw new \Exception('角色不存在');
        }
        $model->delete();
        RoleRelationModel::where('role_id', $model->id)
            ->orWhere('role_link', $model->id)->delete();
    }

    public static function addLink($from, $to, $title) {
        if ($from < 1 || $to < 1) {
            return;
        }
        $model = RoleRelationModel::where('role_id', $from)
            ->where('role_link', $to)->first();
        if ($model) {
            $model->title = $title;
        } else {
            $model = new RoleRelationModel([
                'role_id' => $from,
                'role_link' => $to,
                'title' => $title
            ]);
        }
        $model->save();
        return $model;
    }

    public static function removeLink($from, $to) {
        if ($from < 1 || $to < 1) {
            return;
        }
        RoleRelationModel::where('role_id', $from)
            ->where('role_link', $to)->delete();
    }
}