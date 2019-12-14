<?php
namespace Module\MicroBlog\Domain\Repositories;

use Module\Auth\Domain\Model\UserModel;
use Module\MicroBlog\Domain\Model\CommentModel;
use Module\MicroBlog\Domain\Model\LogModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Exception;

class MicroRepository {

    /**
     * 不允许频繁发布
     * @return bool
     * @throws \Exception
     */
    public static function canPublish() {
        $time = MicroBlogModel::where('user_id', auth()->id())
            ->max('created_at');
        return !$time || $time < time() - 300;
    }

    public static function create($content, $images = null) {
        $model = MicroBlogModel::create([
            'user_id' => auth()->id(),
            'content' => $content
        ]);
        if ($model) {
            self::at($content, $model->id);
        }
        return $model;
    }

    /**
     * 评论
     * @param $content
     * @param $micro_id
     * @param int $parent_id
     * @param bool $is_forward 是否转发
     * @return CommentModel
     */
    public static function comment($content,
                                   $micro_id,
                                   $parent_id = 0,
                                   $is_forward = false) {
        $model = MicroBlogModel::find($micro_id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $comment = CommentModel::create([
            'content' => $content,
            'parent_id' => $parent_id,
            'user_id' => auth()->id(),
            'micro_id' => $model->id,
        ]);
        if (!$comment) {
            throw new Exception('评论失败');
        }
        if (!$is_forward) {
            self::at($content, $model->id);
            return $comment;
        }
        if ($model->foreard_id > 0) {
            $sourceUser = UserModel::where('id', $model->id)->first('name');
            $content = sprintf('%s// @%s : %s', $content, $sourceUser->name, $model->content);
        }
        MicroBlogModel::create([
            'user_id' => auth()->id(),
            'content' => $content,
            'forward_id' => $model->foreard_id > 0 ? $model->foreard_id :
                $model->id
        ]);
        $model->forward ++;
        $model->save();
        return $comment;
    }

    public static function collect($id) {
        return self::toggleLog($id,
            LogModel::ACTION_COLLECT, LogModel::TYPE_MICRO_BLOG);
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
        $model = MicroBlogModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $res = self::toggleLog($id,
            LogModel::ACTION_RECOMMEND, LogModel::TYPE_MICRO_BLOG);
        $model->recommend += $res ? 1 : -1;
        $model->save();
        return $model;
    }

    /**
     * 转发
     * @param $id
     * @param $content
     * @param bool $is_comment 是否并评论
     * @return MicroBlogModel
     * @throws Exception
     */
    public static function forward($id, $content, $is_comment = false) {
        $source = MicroBlogModel::find($id);
        if (!$source) {
            throw new Exception('id 错误');
        }
        $model = MicroBlogModel::create([
            'user_id' => auth()->id(),
            'content' => $content,
            'forward_id' => $source->id
        ]);
        if (!$model) {
            throw new Exception('转发失败');
        }
        $source->forward ++;
        $source->save();
        self::at($content, $model->id);
        if (!$is_comment) {
            return $model;
        }
        $comment = CommentModel::create([
            'content' => $content,
            'user_id' => auth()->id(),
            'micro_id' => $source->id,
        ]);
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

    public static function at($content, $id) {
        if (!preg_match_all('/@(\S+?)\s/', $content, $matches)) {
            return;
        }
        foreach ($matches[1] as $name) {

        }
    }
}
