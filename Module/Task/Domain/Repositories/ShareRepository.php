<?php
namespace Module\Task\Domain\Repositories;

use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Model\TaskShareModel;
use Module\Task\Domain\Model\TaskShareUserModel;

class ShareRepository {

    public static function getList() {
        $ids = TaskShareUserModel::where('user_id', auth()->id())
            ->where('deleted_at', 0)->pluck('share_id');
        return TaskShareModel::with('task')
            ->whereIn('id', $ids)->orderBy('id', 'desc')->page();
    }

    public static function myList() {
        return TaskShareModel::with('task')
            ->where('user_id', auth()->id())->orderBy('id', 'desc')->page();
    }

    public static function isShareUser($taskIds, $user_id) {
        $shareIds = TaskShareModel::whereIn('task_id', (array)$taskIds)
            ->pluck('id');
        if (empty($shareIds)) {
            return false;
        }
        $count = TaskShareUserModel::whereIn('share_id', $shareIds)
            ->where('user_id', $user_id)
            ->where('deleted_at', 0)->count();
        return $count > 0;
    }

    public static function create(array $data) {
        $task = TaskModel::findWithAuth($data['id']);
        if (empty($task)) {
            throw new \Exception('任务错误');
        }
        $share = TaskShareModel::create([
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'share_type' => isset($data['share_type']) ? $data['share_type'] : 0,
            'share_rule' => isset($data['share_rule']) ? $data['share_rule'] : '',
        ]);
        if (empty($share)) {
            throw new \Exception('创建分享失败');
        }
        return $share;
    }

    public static function detail($id) {
        $share = TaskShareModel::find($id);
        if (empty($share)) {
            throw new \Exception('数据错误');
        }
        if ($share->user_id != auth()->id()) {
            static::addUser($share, auth()->id());
        }
        $share->task;
        return $share;
    }

    public static function addUser(TaskShareModel $share, $user_id) {
        $log = TaskShareUserModel::where('share_id', $share->id)
            ->where('user_id', $user_id)->first();
        if ($log && $log->deleted_at > 0) {
            throw new \Exception('你已被移除');
        }
        if ($log) {
            return;
        }
        if (time() - $share->getAttributeSource('created_at') > 86400) {
            throw new \Exception('分享已过期');
        }
        TaskShareUserModel::create([
            'share_id' => $share->id,
            'user_id' => $user_id
        ]);
    }

    public static function remove($id) {
        $share = TaskShareModel::findWithAuth($id);
        if (empty($share)) {
            throw new \Exception('无权限操作');
        }
        $share->delete();
        TaskShareUserModel::where('share_id', $share->id)->delete();
    }

    public static function removeUser($id, $user_id) {
        $share = TaskShareModel::findWithAuth($id);
        if (empty($share)) {
            throw new \Exception('无权限操作');
        }
        TaskShareUserModel::where('share_id', $share->id)
            ->where('user_id', $user_id)->update([
                'deleted_at' => time()
            ]);
    }
}