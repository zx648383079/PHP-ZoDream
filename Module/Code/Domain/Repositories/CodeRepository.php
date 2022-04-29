<?php
namespace Module\Code\Domain\Repositories;

use Module\Code\Domain\Model\TagModel;
use Module\Code\Domain\Model\CommentModel;
use Module\Code\Domain\Model\LogModel;
use Module\Code\Domain\Model\CodeModel;
use Exception;
use Zodream\Helpers\Html;

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
                'content' => Html::text($tag),
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

    
    public static function delete(int $id) {
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

    public static function deleteComment(int $id) {
        $comment = CommentModel::find($id);
        if (!$comment) {
            throw new Exception('id 错误');
        }
        $model = CodeModel::find($comment->micro_id);
        if ($model->user_id != auth()->id() && $comment->user_id != auth()->id()) {
            throw new Exception('无法删除');
        }
        $comment->delete();
        $model->comment_count --;
        $model->save();
        return $model;
    }

    public static function collect(int $id) {
        $model = CodeModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        if ($model->user_id == auth()->id()) {
            throw new Exception('自己无法收藏');
        }
        $res = LogRepository::toggleLog(LogModel::TYPE_CODE, LogModel::ACTION_COLLECT, $id);
        if ($res > 0) {
            $model->collect_count ++;
            $model->is_collected = true;
        } else {
            $model->collect_count --;
            $model->is_collected = false;
        }
        $model->save();
        return $model;
    }

    public static function recommend(int $id) {
        $model = CodeModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $res = LogRepository::toggleLog(LogModel::TYPE_CODE, LogModel::ACTION_RECOMMEND, $id);
        $model->recommend_count += $res > 0 ? 1 : -1;
        $model->save();
        return $model;
    }

}
