<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Repositories;

use Exception;
use Module\Finance\Domain\Model\ConsumptionChannelModel;

class ChannelRepository {

    /**
     * @return ConsumptionChannelModel[]
     */
    public static function all() {
        return ConsumptionChannelModel::auth()->orderBy('id', 'desc')->all();
    }

    /**
     * 获取
     * @param int $id
     * @return ConsumptionChannelModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = ConsumptionChannelModel::auth()->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('产品不存在');
        }
        return $model;
    }

    /**
     * 保存
     * @param array $data
     * @return ConsumptionChannelModel
     * @throws Exception
     */
    public static function save(array $data) {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = ConsumptionChannelModel::auth()->where('id', $data['id'])->first();
            if (empty($model)) {
                throw new Exception('不存在');
            }
        } else {
            $model = new ConsumptionChannelModel();
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
        return ConsumptionChannelModel::auth()->where('id', $id)->delete();
    }



}