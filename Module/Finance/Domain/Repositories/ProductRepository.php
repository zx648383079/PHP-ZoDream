<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Repositories;

use Exception;
use Module\Finance\Domain\Model\FinancialProductModel;

class ProductRepository {

    /**
     * @return FinancialProductModel[]
     */
    public static function all() {
        return FinancialProductModel::auth()->orderBy('id', 'desc')->all();
    }

    /**
     * 获取
     * @param int $id
     * @return FinancialProductModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = FinancialProductModel::auth()->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('产品不存在');
        }
        return $model;
    }

    /**
     * 保存
     * @param array $data
     * @return FinancialProductModel
     * @throws Exception
     */
    public static function save(array $data) {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = FinancialProductModel::auth()->where('id', $data['id'])->first();
            if (empty($model)) {
                throw new Exception('不存在');
            }
        } else {
            $model = new FinancialProductModel();
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    /**
     * 删除产品
     * @param int $id
     * @return mixed
     */
    public static function remove(int $id) {
        return FinancialProductModel::auth()->where('id', $id)->delete();
    }

    /**
     * 改变状态
     * @param int $id
     * @return FinancialProductModel
     * @throws Exception
     */
    public static function change(int $id) {
        $model = self::get($id);
        $model->status = $model->status > 0 ? 0 : 1;
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

}