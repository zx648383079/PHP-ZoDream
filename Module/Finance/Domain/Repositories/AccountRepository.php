<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Repositories;

use Module\Finance\Domain\Model\AccountSimpleModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Exception;

final class AccountRepository {

    /**
     * 获取简单的select
     * @return array|null
     */
    public static function getItems() {
        return AccountSimpleModel::auth()->orderBy('id', 'asc')->get();
    }

    /**
     * @return MoneyAccountModel[]
     */
    public static function all() {
        return MoneyAccountModel::auth()->orderBy('id', 'desc')->all();
    }

    /**
     * 获取
     * @param int $id
     * @return MoneyAccountModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = MoneyAccountModel::auth()->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('产品不存在');
        }
        return $model;
    }

    /**
     * 保存
     * @param array $data
     * @return MoneyAccountModel
     * @throws Exception
     */
    public static function save(array $data) {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = MoneyAccountModel::auth()->where('id', $data['id'])->first();
            if (empty($model)) {
                throw new Exception('不存在');
            }
        } else {
            $model = new MoneyAccountModel();
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    /**
     * 软删除产品
     * @param int $id
     * @return mixed
     */
    public static function softDelete(int $id) {
        return MoneyAccountModel::auth()->where('id', $id)->update([
            'deleted_at' => time()
        ]);
    }

    public static function remove(int $id) {
        return MoneyAccountModel::auth()->where('id', $id)->delete();
    }

    /**
     * 改变状态
     * @param int $id
     * @return MoneyAccountModel
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