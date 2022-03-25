<?php
declare(strict_types=1);
namespace Module\Code\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Code\Domain\Model\CommentModel;
use Module\Code\Domain\Model\LogModel;

class CommentRepository {
    public static function getList(string $keywords = '', int $user = 0, int $micro = 0) {
        return CommentModel::with('user')
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($micro > 0, function ($query) use ($micro) {
                $query->where('micro_id', $micro);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['content']);
            })->orderBy('id', 'desc')->page();
    }

    public static function remove(int $id) {
        CommentModel::where('id', $id)->delete();
    }

    public static function commentList(int $micro, int $parent_id = 0, string $sort = 'created_at', string $order = 'desc') {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['created_at', 'id']);
        return CommentModel::with('replies', 'user')
            ->where('micro_id', $micro)
            ->where('parent_id', $parent_id)->orderBy($sort, $order)->page();
    }

    public static function agree(int $id) {
        $model = CommentModel::find($id);
        if (!$model) {
            throw new Exception('评论不存在');
        }
        $res = LogRepository::toggleLog(LogModel::TYPE_COMMENT,
            LogModel::ACTION_AGREE, $id,
            [LogModel::ACTION_AGREE, LogModel::ACTION_DISAGREE]);
        if ($res < 1) {
            $model->agree_count --;
        } elseif ($res == 1) {
            $model->agree_count ++;
            $model->disagree_count --;
        } elseif ($res == 2) {
            $model->agree_count ++;
        }
        $model->save();
        return $model;
    }

    public static function disagree(int $id) {
        $model = CommentModel::find($id);
        if (!$model) {
            throw new Exception('评论不存在');
        }
        $res = LogRepository::toggleLog(LogModel::TYPE_COMMENT,
            LogModel::ACTION_DISAGREE, $id,
            [LogModel::ACTION_AGREE, LogModel::ACTION_DISAGREE]);
        if ($res < 1) {
            $model->disagree_count --;
        } elseif ($res == 1) {
            $model->agree_count --;
            $model->disagree_count ++;
        } elseif ($res == 2) {
            $model->disagree_count ++;
        }
        return $model;
    }
}
