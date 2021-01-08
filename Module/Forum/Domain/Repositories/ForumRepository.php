<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Module\Forum\Domain\Model\ForumModel;

class ForumRepository {

    public static function getList(string $keywords = '', int $parent = 0) {
        return ForumModel::where('parent_id', $parent)
            ->when(!empty($keywords), function ($query) {
                ForumModel::searchWhere($query, 'name');
            })->page();
    }

    public static function get(int $id) {
        return ForumModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = ForumModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        ForumModel::where('id', $id)->delete();
    }

    public static function all() {
        return ForumModel::tree()->makeTreeForHtml();
    }
}