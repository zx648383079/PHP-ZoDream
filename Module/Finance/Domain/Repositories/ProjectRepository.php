<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Repositories;

use Exception;
use Module\Finance\Domain\Model\FinancialProjectModel;
use Module\Finance\Domain\Model\LogModel;

class ProjectRepository {

    /**
     * @return FinancialProjectModel[]
     */
    public static function all() {
        return FinancialProjectModel::auth()->with('product')->orderBy('id', 'desc')->all();
    }

    /**
     * 获取
     * @param int $id
     * @return FinancialProjectModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = FinancialProjectModel::auth()->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('项目不存在');
        }
        return $model;
    }

    /**
     * 保存
     * @param array $data
     * @return FinancialProjectModel
     * @throws Exception
     */
    public static function save(array $data) {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = FinancialProjectModel::auth()->where('id', $data['id'])->first();
            if (empty($model)) {
                throw new Exception('不存在');
            }
        } else {
            $model = new FinancialProjectModel();
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
        return FinancialProjectModel::auth()->where('id', $id)->delete();
    }

    /**
     * 项目收益
     * @param int $id
     * @param float $money
     * @return LogModel
     * @throws Exception
     */
    public static function earnings(int $id, float $money) {
        $project = self::get($id);
        $model = new LogModel();
        $model->money = $money;
        $model->account_id = $project->account_id;
        $model->project_id = $project->id;
        $model->type = LogModel::TYPE_INCOME;
        $model->user_id = auth()->id();
        $model->happened_at = date('Y-m-d H:i:s');
        $model->remark = sprintf('理财项目 %s 收益', $project->name);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

}