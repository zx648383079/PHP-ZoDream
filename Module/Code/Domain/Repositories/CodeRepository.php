<?php
declare(strict_types=1);
namespace Module\Code\Domain\Repositories;

use Domain\Providers\ActionLogProvider;
use Domain\Providers\CommentProvider;
use Domain\Providers\TagProvider;
use Module\Code\Domain\Model\CodeModel;
use Exception;

class CodeRepository {

    const BASE_KEY = 'code';

    const LOG_TYPE_CODE = 0;
    const LOG_TYPE_COMMENT = 1;

    const LOG_ACTION_RECOMMEND = 1;
    const LOG_ACTION_COLLECT = 2;
    const LOG_ACTION_AGREE = 3;
    const LOG_ACTION_DISAGREE = 4;

    public static function comment(): CommentProvider {
        return new CommentProvider(self::BASE_KEY);
    }

    public static function log(): ActionLogProvider {
        return new ActionLogProvider(self::BASE_KEY);
    }

    public static function tag(): TagProvider {
        return new TagProvider(self::BASE_KEY);
    }

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

    public static function create(string $content, array|string $tags, string $lang) {
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
        self::tag()->bindTag($model->id, $tags);
        return $model;
    }

    /**
     * 评论
     * @param $content
     * @param $code_id
     * @param int $parent_id
     * @return array
     * @throws Exception
     */
    public static function commentSave(string $content,
                                   int $code_id,
                                   int $parent_id = 0) {
        $model = CodeModel::find($code_id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $comment = self::comment()->save([
            'content' => $content,
            'parent_id' => $parent_id,
            'target_id' => $model->id,
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
        self::tag()->removeLink($id);
        self::comment()->removeByTarget($id);
        return true;
    }

    public static function deleteComment(int $id) {
        $comment = self::comment()->get($id);
        if (!$comment) {
            throw new Exception('id 错误');
        }
        $model = CodeModel::find($comment['target_id']);
        if ($model->user_id != auth()->id() && $comment['user_id'] != auth()->id()) {
            throw new Exception('无法删除');
        }
        self::comment()->remove($comment['id']);
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
        $res = self::log()->toggleLog(self::LOG_TYPE_CODE, self::LOG_ACTION_RECOMMEND, $id);
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
        $res = self::log()->toggleLog(self::LOG_TYPE_CODE, self::LOG_ACTION_RECOMMEND, $id);
        $model->recommend_count += $res > 0 ? 1 : -1;
        $model->save();
        return $model;
    }

}
