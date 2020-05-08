<?php
namespace Module\Shop\Domain\Repositories;

use Module\Shop\Domain\Models\RegionModel;
use Module\Shop\Domain\Models\Scene\Address;
use Exception;

class AddressRepository {

    public static function getList() {
        return Address::with('region')->where('user_id', auth()->id())->orderBy('id desc')->page();;
    }

    public static function get($id) {
        return Address::with('region')->where('user_id', auth()->id())
            ->where('id', $id)->first();
    }

    public static function remove($id) {
        $model = self::get($id);
        if (!$model) {
            throw new Exception('地址错误');
        }
        return $model->delete();
    }

    /**
     * 保存地址
     * @param array $data
     * @return Address
     * @throws Exception
     */
    public static function save(array $data) {
        if ($data['region_id'] < 1 && !empty($data['region_name'])) {
            $data['region_id'] = RegionModel::findIdByName($data['region_name']);
        }
        if (isset($data['id']) && $data['id'] > 0
            && isset($data['tel']) && strpos($data['tel'], '****') > 0) {
            unset($data['tel']);
        }
        $data['user_id'] = auth()->id();
        $model = isset($data['id']) && $data['id'] > 0 ? self::get($data['id'])
            : new Address();
        if (!$model) {
            throw new Exception('地址错误');
        }
        $model->set($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        if (isset($data['is_default']) && $data['is_default']) {
            Address::defaultId($model->id);
        }
        return $model;
    }

    /**
     * 设为默认
     * @param $id
     * @return int
     * @throws Exception
     */
    public static function setDefault($id) {
        $model = self::get($id);
        if (!$model) {
            throw new Exception('地址错误');
        }
        return Address::defaultId($id);
    }

    public static function getDefault() {
        $id = Address::defaultId();
        if ($id > 0) {
            return Address::where('id', $id)->where('user_id', auth()->id())->first();
        }
        return Address::where('user_id', auth()->id())->first();
    }
}