<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\BrandModel;
use Module\Shop\Domain\Models\GoodsModel;

class BrandRepository {
    public static function getList(string $keywords = '') {
        return BrandModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->page();
    }

    public static function get(int $id) {
        return BrandModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = BrandModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        BrandModel::where('id', $id)->delete();
    }

    public static function refresh() {
        BrandModel::refreshPk(function ($old_id, $new_id) {
            GoodsModel::where('brand_id', $old_id)->update([
                'brand_id' => $new_id
            ]);
        });
    }

    public static function all() {
        return BrandModel::query()->get('id', 'name');
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            BrandModel::query(),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }

}