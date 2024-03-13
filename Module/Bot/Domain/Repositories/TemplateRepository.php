<?php
declare(strict_types=1);
namespace Module\Bot\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Bot\Domain\Model\EditorTemplateCategoryModel;
use Module\Bot\Domain\Model\EditorTemplateModel;
use Zodream\Html\Tree;

class TemplateRepository {

    public static function getList(string $keywords = '', int $type = 0, int $category = 0) {
        return EditorTemplateModel::when($type >= 0, function ($query) use ($type) {
            $query->where('type', $type);
        })->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->page();
    }

    public static function get(int $id) {
        return EditorTemplateModel::findOrThrow($id, '数据有误');
    }

    public static function remove(int $id) {
        EditorTemplateModel::where('id', $id)->delete();
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = EditorTemplateModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function categoryList(string $keywords = '') {
        return EditorTemplateCategoryModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function category(int $id) {
        return EditorTemplateCategoryModel::findOrThrow($id, '数据有误');
    }

    public static function categorySave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = EditorTemplateCategoryModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function categoryRemove(int $id) {
        EditorTemplateCategoryModel::where('id', $id)->delete();
    }

    public static function typeList(): array {
        $data = [];
        foreach (EditorTemplateModel::$type_list as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $data;
    }

    public static function categoryAll() {
        return (new Tree(EditorTemplateCategoryModel::query()->get()))
            ->makeTreeForHtml();
    }
}