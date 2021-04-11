<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\CMS\Domain\Model\SiteModel;

class SiteRepository {
    public static function getList(string $keywords = '') {
        return SiteModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->orderBy('id', 'asc')->page();
    }

    public static function get(int $id) {
        return SiteModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = SiteModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        SiteModel::where('id', $id)->delete();
    }

    public static function setDefault(int $id) {
        $model = static::get($id);
        $model->is_default = 1;
        $model->save();
        SiteModel::where('id', '<>', $id)->update([
            'is_default' => 0
        ]);
    }

    public static function option(int $id) {
        $model = static::get($id);
        return $model->options;
    }

    public static function optionSave(int $id, array $data) {
        $model = static::get($id);
        $model->options = $data;
        $model->save();
    }

    public static function apply(int $id) {
        CMSRepository::site(new SiteModel(compact('id')));
    }
}