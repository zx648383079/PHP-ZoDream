<?php
declare(strict_types=1);
namespace Module\Task\Domain\Repositories;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Task\Domain\Model\TaskModel;
use Module\Task\Domain\Model\TaskShareModel;
use Module\Task\Domain\Model\TaskShareUserModel;

class ShareRepository {

    public static function getList() {
        $ids = TaskShareUserModel::where('user_id', auth()->id())
            ->where('deleted_at', 0)->pluck('share_id');
        return TaskShareModel::with('task', 'user')
            ->whereIn('id', $ids)->orderBy('id', 'desc')->page();
    }

    public static function myList() {
        return TaskShareModel::with('task')
            ->where('user_id', auth()->id())->orderBy('id', 'desc')->page();
    }

    public static function isShareUser(array|int $taskIds, int $user_id) {
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
        $task = TaskModel::findWithAuth($data['task_id']);
        if (empty($task)) {
            throw new \Exception('任务错误');
        }
        $share = TaskShareModel::create([
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'share_type' => $data['share_type'] ?? 0,
            'share_rule' => $data['share_rule'] ?? '',
        ]);
        if (empty($share)) {
            throw new \Exception('创建分享失败');
        }
        return $share;
    }

    public static function detail(int $id) {
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

    public static function users(int $id) {
        $share = TaskShareModel::find($id);
        if (empty($share)) {
            throw new \Exception('数据错误');
        }
        $userIds = TaskShareUserModel::where('share_id', $id)
            ->where('deleted_at', 0)->pluck('user_id');
        $userIds[] = $share->user_id;
        if (!in_array(auth()->id(), $userIds)) {
            throw new \Exception('无权限操作');
        }
        $items = UserSimpleModel::whereIn('id', $userIds)->get();
        $admin = $roles = $users = [];
        foreach ($items as $item) {
            if ($item->id === $share->user_id) {
                $item->role_name = '所有者';
                $admin[] = $item;
                continue;
            }
            if ($share->user_id === auth()->id()) {
                $item->editable = true;
            }
            $users[] = $item;
        }
        return array_merge($admin, $roles, $users);
    }

    public static function addUser(TaskShareModel $share, int $user_id) {
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

    public static function remove(int $id) {
        $share = TaskShareModel::find($id);
        if (empty($share)) {
            throw new \Exception('分享错误');
        }
        if ($share->user_id == auth()->id()) {
            $share->delete();
            TaskShareUserModel::where('share_id', $share->id)->delete();
            return;
        }
        TaskShareUserModel::where('share_id', $share->id)
            ->where('user_id', auth()->id())->update([
                'deleted_at' => time()
            ]);
    }

    public static function removeUser(int $id, int $user_id) {
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