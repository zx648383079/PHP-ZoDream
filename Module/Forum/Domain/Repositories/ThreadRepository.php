<?php
namespace Module\Forum\Domain\Repositories;

use Exception;
use Module\Forum\Domain\Model\ThreadLogModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;

class ThreadRepository {


    /**
     * 收藏
     * @param $id
     * @return bool
     * @throws Exception
     */
    public static function toggleCollect($id) {
        $model = ThreadModel::find($id);
        if (empty($model)) {
            throw new Exception('帖子不存在');
        }
        $count = ThreadLogModel::query()->where('item_type', ThreadLogModel::TYPE_THREAD)
            ->where('item_id', $model->id)
            ->where('user_id', auth()->id())
            ->where('action', ThreadLogModel::ACTION_COLLECT)
            ->count();
        if ($count > 0) {
            ThreadLogModel::query()->where('item_type', ThreadLogModel::TYPE_THREAD)
                ->where('item_id', $model->id)
                ->where('user_id', auth()->id())
                ->where('action', ThreadLogModel::ACTION_COLLECT)->delete();
            $model->collect_count -= $count;
            $model->save();
            return false;
        }
        $log = ThreadLogModel::create([
            'item_type' => ThreadLogModel::TYPE_THREAD,
            'item_id' => $model->id,
            'user_id' => auth()->id(),
            'action' => ThreadLogModel::ACTION_COLLECT,
        ]);
        if (!$log) {
            throw new Exception('保存未成功');
        }
        $model->collect_count ++;
        $model->save();
        return true;
    }

    /**
     * 是否同意回帖内容
     * @param $id
     * @param bool $agree
     * @return array
     * @throws Exception
     */
    public static function agreePost($id, bool $agree = true) {
        $model = ThreadPostModel::find($id);
        if (empty($model)) {
            throw new Exception('回复不存在');
        }
        $action = $agree ? ThreadLogModel::ACTION_AGREE : ThreadLogModel::ACTION_DISAGREE;
        /** @var ThreadLogModel $log */
        $log = ThreadLogModel::query()
            ->where('item_type', ThreadLogModel::TYPE_THREAD_POST)
            ->where('item_id', $model->id)
            ->where('user_id', auth()->id())
            ->whereIn('action',
                [ThreadLogModel::ACTION_AGREE, ThreadLogModel::ACTION_DISAGREE])
            ->first();
        if ($log && $log->action == $action) {
            throw new Exception('请勿重复点击');
        }
        if (empty($log)) {
            $log = ThreadLogModel::create([
                'item_type' => ThreadLogModel::TYPE_THREAD_POST,
                'item_id' => $model->id,
                'user_id' => auth()->id(),
                'action' => $action,
            ]);
        } else {
            $log->action = $action;
            $log->created_at = time();
            $log->save();
            if ($agree) {
                $model->disagree_count --;
            } else {
                $model->agree_count --;
            }
            $model->save();
        }
        if (!$log) {
            throw new Exception('操作失败');
        }
        if ($agree) {
            $model->agree_count ++;
        } else {
            $model->disagree_count ++;
        }
        $model->save();
        return [
          'is_agree' => $agree,
          'is_disagree' => !$agree,
          'agree_count' => $model->agree_count,
          'disagree_count' => $model->disagree_count
        ];
    }
}