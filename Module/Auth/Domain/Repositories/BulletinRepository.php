<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Exception;
use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Auth\Domain\Model\Bulletin\BulletinUserModel;

class BulletinRepository {

    /**
     * 获取消息列表
     * @param string $keywords
     * @param int $status
     * @return mixed
     * @throws Exception
     */
    public static function getList(string $keywords = '', int $status = 0) {
        return BulletinUserModel::with('bulletin')
            ->when(!empty($keywords), function ($query) {
                $ids = BulletinModel::where(function ($query) {
                    BulletinModel::search($query, 'title');
                })->pluck('id');
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('bulletin_id', $ids);
            })
            ->when($status > 0, function ($query) use ($status) {
                $query->where('status', $status - 1);
            })
            ->where('user_id', auth()->id())
            ->orderBy('status', 'asc')
            ->orderBy('bulletin_id', 'desc')->page();
    }

    /**
     * 取一条消息
     * @param int $id
     * @return mixed
     * @throws Exception
     */
    public static function read(int $id) {
        $model = BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->first();
        if (empty($model)) {
            throw new Exception('消息不存在');
        }
        $model->status = BulletinUserModel::READ;
        $model->save();
        return $model;
    }

    /**
     * 标记全部已读
     * @throws Exception
     */
    public static function readAll() {
        BulletinUserModel::where('user_id', auth()->id())
            ->where('status', 0)->update([
                'status' => BulletinUserModel::READ,
                'updated_at' => time()
            ]);
    }

    /**
     * 删除一条消息
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public static function remove(int $id) {
        $model = BulletinUserModel::where('user_id', auth()->id())
            ->where('bulletin_id', $id)->first();
        if (empty($model)) {
            throw new Exception('消息不存在');
        }
        return $model->delete();
    }
}