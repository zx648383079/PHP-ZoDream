<?php
declare(strict_types=1);
namespace Module\MicroBlog\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Repositories\BulletinRepository;
use Module\SEO\Domain\Repositories\EmojiRepository;
use Module\MicroBlog\Domain\LinkRule;
use Module\Auth\Domain\Model\UserModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Module\MicroBlog\Domain\Model\AttachmentModel;
use Module\MicroBlog\Domain\Model\BlogTopicModel;
use Module\MicroBlog\Domain\Model\CommentModel;
use Module\MicroBlog\Domain\Model\LogModel;
use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Exception;
use Module\SEO\Domain\Option;
use Zodream\Helpers\Html;

class MicroRepository {

    public static function getList(
        string $sort = 'new', string $keywords = '',
        int $id = 0, int $user = 0, int $topic = 0) {
        return MicroBlogModel::with('user', 'attachment')
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if ($sort == 'new') {
                    return $query->orderBy('created_at', 'desc');
                }
                if ($sort == 'recommend') {
                    return $query->orderBy('recommend_count', 'desc');
                }
            })->when(!empty($keywords) && $id < 1, function ($query) {
                SearchModel::searchWhere($query, ['content']);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($topic > 0, function ($query) use ($topic) {
                $itemId = BlogTopicModel::where('topic_id', $topic)
                    ->pluck('micro_id');
                if (empty($itemId)) {
                    return $query->isEmpty();
                }
                $query->whereIn('id', $itemId);
            })
            ->page();
    }

    /**
     * 不允许频繁发布
     * @return bool
     * @throws \Exception
     */
    public static function canPublish() {
        $limit = Option::value('micro_time_limit', 300);
        if (empty($limit) || $limit < 10) {
            return true;
        }
        $time = MicroBlogModel::where('user_id', auth()->id())
            ->max('created_at');
        return !$time || $time < time() - $limit;
    }


    public static function create(string $content, array $files = [], string $source = 'web') {
        return self::createWithRule($content, [], $files, $source);
    }

    public static function createWithRule(string $content, array $extraRules = [], array $files = [], string $source = 'web') {
        $model = MicroBlogModel::createOrThrow([
            'user_id' => auth()->id(),
            'content' => Html::text($content),
            'source' => $source
        ]);
        $extraRules = array_merge(
            $extraRules,
            self::at($content, $model->id),
            self::topic($content, $model->id),
            EmojiRepository::renderRule($content)
        );
        if (!empty($extraRules)) {
            $model->extra_rule = $extraRules;
            $model->save();
        }
        if (empty($files)) {
            return $model;
        }
        $data = [];
        foreach ($files as $file) {
            $thumb = $file;
            if (is_array($file)) {
                $thumb = $file['thumb'];
                $file = $file['file'];
            }
            if (empty($file)) {
                continue;
            }
            $data[] = [
                'thumb' => $thumb,
                'file' => $file,
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
     * @param string $content
     * @param int $micro_id
     * @param int $parent_id
     * @param bool $is_forward 是否转发
     * @return CommentModel
     * @throws Exception
     */
    public static function comment(string $content,
                                   int $micro_id,
                                   int $parent_id = 0,
                                   bool $is_forward = false) {
        $model = MicroBlogModel::find($micro_id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $content = Html::text($content);
        $comment = CommentModel::createOrThrow([
            'content' => $content,
            'parent_id' => $parent_id,
            'user_id' => auth()->id(),
            'micro_id' => $model->id,
        ]);
        $extraRules = array_merge(
            self::at($content, 0, 1),
            EmojiRepository::renderRule($content),
        );
        if (!empty($extraRules)) {
            $comment->extra_rule = $extraRules;
            $comment->save();
        }
        if ($is_forward) {
            if ($model->forward_id > 0) {
                $sourceUser = UserModel::where('id', $model->user_id)->first('name');
                $content = sprintf('%s// @%s : %s', $content, $sourceUser->name, $model->content);
            }
            $forwardModel = MicroBlogModel::create([
                'user_id' => auth()->id(),
                'content' => $content,
                'forward_id' => $model->forward_id > 0 ? $model->forward_id :
                    $model->id,
                'forward_count' => 1,
                'source' => 'web'
            ]);
            $extraRules = array_merge(
                self::at($content, 0),
                self::topic($content, $forwardModel->id),
                EmojiRepository::renderRule($content),
            );
            if (!empty($extraRules)) {
                $forwardModel->extra_rule = $extraRules;
                $forwardModel->save();
            }
            $model->forward_count ++;
        }
        self::at($content, $model->id);
        $model->comment_count ++;
        $model->save();
        return $comment;
    }

    public static function collect(int $id) {
        $model = MicroBlogModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        if ($model->user_id == auth()->id()) {
            throw new Exception('自己无法收藏');
        }
        $res = LogRepository::toggleLog(LogModel::TYPE_MICRO_BLOG, LogModel::ACTION_COLLECT, $id);
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

    public static function removeSelf(int $id) {
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

    public static function remove(int $id) {
        $model = MicroBlogModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $model->delete();
        CommentModel::where('micro_id', $id)->delete();
        AttachmentModel::where('micro_id', $id)->delete();
        BlogTopicModel::where('micro_id', $id)->delete();
    }

    public static function deleteComment(int $id) {
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


    public static function recommend(int $id) {
        $model = MicroBlogModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $res = LogRepository::toggleLog(LogModel::TYPE_MICRO_BLOG, LogModel::ACTION_RECOMMEND, $id);
        $model->recommend_count += $res > 0 ? 1 : -1;
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
    public static function forward($id, $content, bool $is_comment = false) {
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
        $extraRules = array_merge(
            self::at($content, $model->id),
            self::topic($content, $model->id),
            EmojiRepository::renderRule($content)
        );
        if (!empty($extraRules)) {
            $model->extra_rule = $extraRules;
            $model->save();
        }
        if ($is_comment) {
            CommentModel::create([
                'content' => $content,
                'extra_rule' => $extraRules,
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
     * at 人
     * @param string $content
     * @param int $itemId
     * @return array 返回规则
     * @throws Exception
     */
    public static function at(string $content, int $itemId = 0, int $itemType = 0): array {
        if (empty($content) || !str_contains($content, '@')) {
            return [];
        }
        if (!preg_match_all('/@(\S+?)\s/', $content, $matches, PREG_SET_ORDER)) {
            return [];
        }
        $names = array_column($matches, 0, 1);
        $users = UserSimpleModel::whereIn('name', array_keys($names))->asArray()->get();
        if (empty($users)) {
            return [];
        }
        $rules = [];
        $currentUser = auth()->id();
        $userIds = [];
        foreach ($users as $user) {
            if ($user['id'] != $currentUser) {
                $userIds[] = $user['id'];
            }
            $rules[] = LinkRule::formatUser($names[$user['name']], intval($user['id']));
        }
        if ($itemId < 1 || !empty($userIds)) {
            return $rules;
        }
        if ($itemType < 1) {
            BulletinRepository::sendAt($userIds, '我在微博提到了你', 'micro/'.$itemId);
        }
        return $rules;
    }

    /**
     * 生成话题规则
     * @param string $content
     * @param int $id
     * @return array
     */
    public static function topic(string $content, int $id): array {
        if (empty($content) || !str_contains($content, '#')) {
            return [];
        }
        if (!preg_match_all('/#(\S+?)#(\s|$)/', $content, $matches, PREG_SET_ORDER)) {
            return [];
        }
        $items = [];
        foreach ($matches as $match) {
            $name = trim($match[1]);
            if (!empty($name)) {
                $items[$name][] = $match[0];
            }
        }
        $topicItems = TopicRepository::bind(array_keys($items), $id);
        if (empty($topicItems)) {
            return [];
        }
        $topicItems = array_column($topicItems, 'id', 'name');
        $rules = [];
        foreach ($items as $name => $item) {
            if (!isset($topicItems[$name])) {
                continue;
            }
            foreach ($item as $i) {
                $rules[] = LinkRule::formatTopic($i, intval($topicItems[$name]));
            }
        }
        return $rules;
    }

    /**
     * 创建分享
     * @param string $title
     * @param string $summary
     * @param string $url
     * @param string|array $pics
     * @param string $content
     * @param string $source
     * @return MicroBlogModel
     */
    public static function share(string $title, string $summary, string $url, string|array $pics, string $content, string $source = '') {
        $tag = Html::text($title);
        $content = sprintf("%s \n【%s】%s%s",
            Html::text($content), $tag,
            empty($source) ? '' : sprintf(' - 分享自 @%s ', Html::text($source))
            , Html::text($summary));
        $extraRule = [
            LinkRule::formatLink($tag, $url)
        ];
        return static::createWithRule($content, $extraRule, (array)$pics);
    }
}
