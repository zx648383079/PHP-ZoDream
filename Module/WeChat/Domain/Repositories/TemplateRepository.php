<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Module\WeChat\Domain\Model\MediaTemplateModel;

class TemplateRepository {

    public static function getList(int $type = 0) {
        return MediaTemplateModel::when($type >= 0, function ($query) use ($type) {
            $query->where('type', $type);
        })->page();
    }

    public static function get(int $id) {
        return MediaTemplateModel::findOrThrow($id, '数据有误');
    }

    public static function remove(int $id) {
        MediaTemplateModel::where('id', $id)->delete();
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = MediaTemplateModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function typeList(): array {
        $data = [];
        foreach (MediaTemplateModel::$type_list as $value => $name) {
            $data[] = compact('name', 'value');
        }
        return $data;
    }
}