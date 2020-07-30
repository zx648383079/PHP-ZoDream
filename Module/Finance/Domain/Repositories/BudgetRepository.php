<?php
namespace Module\Finance\Domain\Repositories;

use Module\Finance\Domain\Model\BudgetModel;
use Exception;

class BudgetRepository {

    public static function getList() {
        return BudgetModel::auth()->where('deleted_at', 0)->orderBy('id', 'desc')->page();
    }

    public static function refreshSpent() {
        $items = BudgetModel::auth()->get();
        foreach ($items as $item) {
            $item->refreshSpent();
        }
    }

    /**
     * 获取
     * @param int $id
     * @return BudgetModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = BudgetModel::auth()->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('产品不存在');
        }
        return $model;
    }

    /**
     * 保存
     * @param array $data
     * @return BudgetModel
     * @throws Exception
     */
    public static function save(array $data) {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = BudgetModel::auth()->where('id', $data['id'])->first();
            if (empty($model)) {
                throw new Exception('不存在');
            }
        } else {
            $model = new BudgetModel();
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
        return BudgetModel::auth()->where('id', $id)->update([
            'deleted_at' => time()
        ]);
    }

    public static function remove(int $id) {
        return BudgetModel::auth()->where('id', $id)->delete();
    }

    /**
     * 获取统计
     * @param int $id
     * @return array
     * @throws Exception
     */
    public static function statistics(int $id) {
        $data = self::get($id);
        $log_list = $data->getLogs();
        $sum = array_sum($log_list);
        $budget_sum = count($log_list) * $data->budget;
        return compact('data', 'log_list', 'sum', 'budget_sum');
    }
}