<?php
namespace Module\MicroBlog\Domain\Repositories;

use Module\Auth\Domain\Model\UserModel;
use Module\MicroBlog\Domain\Model\AttachmentModel;
use Module\MicroBlog\Domain\Model\CommentModel;
use Module\MicroBlog\Domain\Model\LogModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Exception;
use Zodream\Helpers\Html;

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

    public static function create($content, $images = null, $source = 'web') {
        $model = MicroBlogModel::create([
            'user_id' => auth()->id(),
            'content' => Html::text($content),
            'source' => $source
        ]);
        if (!$model) {
            throw new Exception('发送失败');
        }
        self::at($content, $model->id);
        if (empty($images)) {
            return $model;
        }
        $data = [];
        foreach ($images as $image) {
            if (empty($image)) {
                continue;
            }
            $data[] = [
                'thumb' => $image,
                'file' => $image,
                'micro_id' => $model->id
            ];
        }
        if (empty($data)) {
            return $model;
        }
        AttachmentModel::query()->insert($data);
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
                                   $micro_id,
                                   $parent_id = 0,
                                   $is_forward = false) {
        $model = MicroBlogModel::find($micro_id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $comment = CommentModel::create([
            'content' => Html::text($content),
            'parent_id' => $parent_id,
            'user_id' => auth()->id(),
            'micro_id' => $model->id,
        ]);
        if (!$comment) {
            throw new Exception('评论失败');
        }
        if ($is_forward) {
            if ($model->forward_id > 0) {
                $sourceUser = UserModel::where('id', $model->id)->first('name');
                $content = sprintf('%s// @%s : %s', $content, $sourceUser->name, $model->content);
            }
            MicroBlogModel::create([
                'user_id' => auth()->id(),
                'content' => Html::text($content),
                'forward_id' => $model->forward_id > 0 ? $model->forward_id :
                    $model->id,
                'forward_count' => 1,
                'source' => 'web'
            ]);
            $model->forward_count ++;
        }
        self::at($content, $model->id);
        $model->comment_count ++;
        $model->save();
        return $comment;
    }

    public static function collect($id) {
        $model = MicroBlogModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        if ($model->user_id == auth()->id()) {
            throw new Exception('自己无法收藏');
        }
        $res = self::toggleLog($id,
            LogModel::ACTION_COLLECT, LogModel::TYPE_MICRO_BLOG);
        $model->collect_count += $res ? 1 : -1;
        $model->save();
        return $model;
    }

    public static function delete($id) {
        $model = MicroBlogModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        if ($model->user_id != auth()->id()) {
            throw new Exception('无法删除');
        }
        $model->delete();
        CommentModel::where('micro_id', $id)->delete();
        AttachmentModel::where('micro_id', $id)->delete();
        return true;
    }

    public static function deleteComment($id) {
        $comment = CommentModel::find($id);
        if (!$comment) {
            throw new Exception('id 错误');
        }
        $model = MicroBlogModel::find($comment->micro_id);
        if ($model->user_id != auth()->id() && $comment->user_id != auth()->id()) {
            throw new Exception('无法删除');
        }
        $comment->delete();
        $model->comment_count --;
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
        $model = MicroBlogModel::find($id);
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
            'forward_id' => $source->id,
            'forward_count' => 1,
            'source' => 'web'
        ]);
        if (!$model) {
            throw new Exception('转发失败');
        }
        if ($is_comment) {
            CommentModel::create([
                'content' => $content,
                'user_id' => auth()->id(),
                'micro_id' => $source->id,
            ]);
            $source->comment_count ++;
        }
        $source->forward_count ++;
        $source->save();
        self::at($content, $model->id);
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

    /**
     * 创建分享
     * @param $title
     * @param $summary
     * @param $url
     * @param $pics
     * @param $content
     * @return MicroBlogModel
     * @throws Exception
     */
    public static function share($title, $summary, $url, $pics, $content) {
        $content = sprintf('%s<a href="%s" target="_blank">%s</a>%s',
            Html::text($content), Html::text($url), Html::text($title), Html::text($summary));
        return static::create($content, $pics);
    }
}
