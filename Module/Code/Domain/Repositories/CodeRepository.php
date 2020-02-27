<?php
namespace Module\Code\Domain\Repositories;

use Module\Auth\Domain\Model\UserModel;
use Module\Code\Domain\Model\TagModel;
use Module\Code\Domain\Model\CollectModel;
use Module\Code\Domain\Model\CommentModel;
use Module\Code\Domain\Model\LogModel;
use Module\Code\Domain\Model\CodeModel;
use Exception;

class CodeRepository {

    /**
     * 不允许频繁发布
     * @return bool
     * @throws \Exception
     */
    public static function canPublish() {
        $time = CodeModel::where('user_id', auth()->id())
            ->max('created_at');
        return !$time || $time < time() - 120;
    }

    public static function create($content, $tags, $lang) {
        $model = CodeModel::create([
            'user_id' => auth()->id(),
            'content' => $content,
            'language' => $lang,
        ]);
        if (!$model) {
            return $model;
        }
        if (empty($tags)) {
            return $model;
        }
        if (!is_array($tags)) {
            $tags = explode(' ', $tags);
        }
        $data = [];
        foreach ($tags as $tag) {
            if (empty($tag)) {
                continue;
            }
            $data[] = [
                'content' => $tag,
                'code_id' => $model->id
            ];
        }
        if (empty($data)) {
            return $model;
        }
        TagModel::query()->insert($data);
        return $model;
    }

    /**
     * 评论
     * @param $content
     * @param $micro_id
     * @param int $parent_id
     * @param bool $is_forward 是否转发
     * @return CommentModel
     * @throws Exception
     */
    public static function comment($content,
                                   $code_id,
                                   $parent_id = 0) {
        $model = CodeModel::find($code_id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $comment = CommentModel::create([
            'content' => $content,
            'parent_id' => $parent_id,
            'user_id' => auth()->id(),
            'code_id' => $model->id,
        ]);
        if (!$comment) {
            throw new Exception('评论失败');
        }
        $model->comment_count ++;
        $model->save();
        return $comment;
    }

    
    public static function delete($id) {
        $model = CodeModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        if ($model->user_id != auth()->id()) {
            throw new Exception('无法删除');
        }
        $model->delete();
        CommentModel::where('code_id', $id)->delete();
        TagModel::where('code_id', $id)->delete();
        return true;
    }

    public static function deleteComment($id) {
        $comment = CommentModel::find($id);
        if (!$comment) {
            throw new Exception('id 错误');
        }
        $model = CodeModel::find($comment->micro_id);
        if ($model->user_id != auth()->id() && $comment->user_id != auth()->id()) {
            throw new Exception('无法删除');
        }
        $comment->delete();
        $mdoel->comment_count --;
        $model->save();
        return $model;
    }

    public static function collect($id) {
        $model = MicroBlogModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $res = self::toggleLog($id,
            LogModel::ACTION_COLLECT, LogModel::TYPE_MICRO_BLOG);
        $model->collect_count += $res ? 1 : -1;
        $model->save();
        return $model;
    }

    public static function toggleLog($id,
                                     $action = LogModel::ACTION_RECOMMEND,
                                     $type = LogModel::TYPE_MICRO_BLOG) {
        $log = LogModel::where([
            'user_id' => auth()->id(),
            'type' => $type,
            'id_value' => $id,
            'action' => $action
        ])->first();
        if ($log) {
            $log->delete();
            return false;
        }
        LogModel::create([
            'type' => $type,
            'id_value' => $id,
            'action' => $action,
            'user_id' => auth()->id()
        ]);
        return true;
    }

    public static function recommend($id) {
        $model = CodeModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $res = self::toggleLog($id,
            LogModel::ACTION_RECOMMEND, LogModel::TYPE_MICRO_BLOG);
        $model->recommend_count += $res ? 1 : -1;
        $model->save();
        return $model;
    }


    /**
     * @param $id
     * @return LogModel|bool
     * @throws Exception
     */
    public static function getCommentLog($id) {
        return LogModel::where([
            'user_id' => auth()->id(),
            'type' => LogModel::TYPE_COMMENT,
            'id_value' => $id,
            'action' => ['in', [LogModel::ACTION_AGREE, LogModel::ACTION_DISAGREE]]
        ])->first();
    }

    public static function agree($id) {
        $model = CommentModel::find($id);
        if (!$model) {
            throw new Exception('评论不存在');
        }
        $log = self::getCommentLog($id);
        if ($log && $log->action == LogModel::ACTION_AGREE) {
            $log->delete();
            $model->agree --;
            $model->save();
            return $model;
        }
        if ($log && $log->action == LogModel::ACTION_DISAGREE) {
            $log->action = LogModel::ACTION_AGREE;
            $log->created_at = time();
            $log->save();
            $model->agree ++;
            $model->disagree --;
            $model->save();
            return $model;
        }
        LogModel::create([
            'type' => LogModel::TYPE_COMMENT,
            'id_value' => $id,
            'action' => LogModel::ACTION_AGREE,
            'user_id' => auth()->id()
        ]);
        $model->agree ++;
        $model->save();
        return $model;
    }

    public static function disagree($id) {
        $model = CommentModel::find($id);
        if (!$model) {
            throw new Exception('评论不存在');
        }
        $log = self::getCommentLog($id);
        if ($log && $log->action == LogModel::ACTION_DISAGREE) {
            $log->delete();
            $model->disagree --;
            $model->save();
            return $model;
        }
        if ($log && $log->action == LogModel::ACTION_AGREE) {
            $log->action = LogModel::ACTION_DISAGREE;
            $log->created_at = time();
            $log->save();
            $model->agree --;
            $model->disagree ++;
            $model->save();
            return $model;
        }
        LogModel::create([
            'type' => LogModel::TYPE_COMMENT,
            'id_value' => $id,
            'action' => LogModel::ACTION_DISAGREE,
            'user_id' => auth()->id()
        ]);
        $model->disagree ++;
        $model->save();
        return $model;
    }
}
