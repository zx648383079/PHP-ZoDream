<?php
namespace Module\Task\Domain\Repositories;

use Module\Task\Domain\Model\TaskCommentModel;
use Module\Task\Domain\Model\TaskLogModel;
use Module\Task\Domain\Model\TaskModel;

class CommentRepository {

    public static function getList(int $task_id) {
        $task = TaskModel::find($task_id);
        if (empty($task)) {
            throw new \Exception('任务错误');
        }
        if ($task->user_id !== auth()->id() &&
            !ShareRepository::isShareUser([$task->id, $task->parent_id], auth()->id())) {
            throw new \Exception('无权限查看评论');
        }
        $taskIds = TaskModel::where('parent_id', $task_id)
            ->pluck('id');
        $taskIds[] = $task_id;
        return TaskCommentModel::with('user')
            ->whereIn('task_id', $taskIds)->orderBy('id', 'desc')->page();
    }

    public static function create(array $data) {
        $task = TaskModel::find($data['task_id']);
        if (empty($task)) {
            throw new \Exception('任务错误');
        }
        if ($task->user_id !== auth()->id() &&
            !ShareRepository::isShareUser([$task->id, $task->parent_id], auth()->id())) {
            throw new \Exception('无权限评论');
        }
        $log_id = 0;
        if ($task->user_id === auth()->id()) {
            $log_id = TaskLogModel::query()
                ->where(function ($query) use ($task) {
                    $query->where('task_id', $task->id)
                        ->orWhere('child_id', $task->id);
                })
                ->where('created_at', '>', time() - 3600)
                ->orderBy('created_at', 'desc')
                ->value('id');
        }
        $comment = TaskCommentModel::create([
            'user_id' => auth()->id(),
            'task_id' => $task->id,
            'log_id' => intval($log_id),
            'content' => $data['content'],
            'type' => isset($data['type']) ? $data['type'] : 0,
            'status' => 0,
        ]);
        if (empty($comment)) {
            throw new \Exception('评论失败');
        }
        return $comment;
    }



    public static function remove(int $id) {
        $comment = TaskCommentModel::find($id);
        if (empty($comment)) {
            throw new \Exception('无权限操作');
        }
        if ($comment->user_id === auth()->id()) {
            $comment->delete();
            return;
        }
        $count = TaskModel::query()->where('task_id', $comment->task_id)
            ->where('user_id', auth()->id())->count();
        if ($count < 1) {
            throw new \Exception('无权限操作');
        }
        $comment->delete();
    }
}