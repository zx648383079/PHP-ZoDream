<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\SiteLogModel;
use Module\CMS\Domain\Scene\SceneInterface;

class CommentRepository {

    const ALLOW_GUEST_COMMENT = true;

    public static function checkArticle(int $article, int|string $category, int|string $model): array {
        FuncHelper::$current['channel'] = FuncHelper::mapId($category, 'channel');
        FuncHelper::$current['content'] = $article;
        $model = FuncHelper::model($model);
        if (empty($model)) {
            throw new Exception('article is error');
        }
        FuncHelper::$current['model'] = $model['id'];
        $scene = CMSRepository::scene()->setModel($model);
        $article = $scene->query()->where('id', $article)->first();
        if (!CMSRepository::isPreview() && $article['status'] != SiteRepository::PUBLISH_STATUS_POSTED) {
            throw new Exception('article is error');
        }
        return $article;
    }

    public static function getList(int $article, int|string $category, int|string $model,
                                   string $keywords = '',
                                   int $parent_id = 0, string $sort = 'created_at',
                                   string $order = 'desc', string $extra = '', int $page = 1, int $prePage = 20) {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, ['created_at', 'parent_id']);
        /** @var SceneInterface $scene */
        list($scene, $modelId) = static::scene($article, $category, $model);
        return $scene->searchComment($keywords, [
            'parent_id' => $parent_id,
            'content_id' => $article,
            'model_id' => $modelId,
        ], sprintf('%s %s', $sort, $order), $extra, $page, $prePage);
    }

    public static function create(string $content,
                                  int $article, int|string $category,
                                  int|string $model,
                                  int $parent_id = 0)
    {
        $data = [];
        if (static::ALLOW_GUEST_COMMENT && auth()->guest()) {
            $data = request()->validate([
                'name' => 'required|string:1-30',
                'email' => 'email',
                'url' => 'string'
            ]);
        } else if (auth()->guest()) {
            throw new Exception(__('Please login in first'), 401);
        }
        /** @var SceneInterface $scene */
        list($scene, $modelId) = static::scene($article, $category, $model);
        $maxFloor = $scene->commentQuery()->where('model_id', $modelId)
            ->where('content_id', $article)->where('parent_id', $parent_id)->max('position');
        if ($parent_id > 0) {
            $pa_id = $scene->commentQuery()->where('model_id', $modelId)
                ->where('content_id', $article)->where('id', $parent_id)->value('parent_id');
            if ($pa_id > 0) {
                $parent_id = $pa_id;
            }
        }
        $res = $scene->insertComment(array_merge($data, [
            'content' => $content,
            'parent_id' => $parent_id,
            'position' => intval($maxFloor) + 1,
            'user_id' => auth()->id(),
            'model_id' => $modelId,
            'content_id' => $article,
            'created_at' => time()
        ]));
        if (!$res) {
            return [];
        }
        $scene->query()->where('id', $article)->updateIncrement('comment_count');
        if ($parent_id > 0) {
            $scene->commentQuery()->where('model_id', $modelId)
                ->where('content_id', $article)->where('id', $parent_id)->updateIncrement('reply_count');
        }
        return [];
    }

    public static function disagree(int $id, int $article, int|string $category, int|string $model)
    {
        /** @var SceneInterface $scene */
        list($scene, $modelId) = static::scene($article, $category, $model);
        $data = $scene->commentQuery()->where('id', $id)->first();
        if (empty($data)) {
            throw new \Exception('评论不存在');
        }
        $res = self::toggleLog($modelId, SiteRepository::TYPE_COMMENT,
            SiteRepository::ACTION_DISAGREE, $id,
            [SiteRepository::ACTION_AGREE, SiteRepository::ACTION_DISAGREE]);
        if ($res < 1) {
            $data['disagree_count'] --;
            $data['agree_type'] = 0;
        } elseif ($res == 1) {
            $data['disagree_count'] ++;
            $data['agree_count'] --;
            $data['agree_type'] = 2;
        } elseif ($res == 2) {
            $data['disagree_count'] ++;
            $data['agree_type'] = 2;
        }
        $scene->commentQuery()->where('id', $id)->update([
            'agree_count' => $data['agree_count'],
            'disagree_count' => $data['disagree_count'],
        ]);
        return $data;
    }

    public static function agree(int $id, int $article, int|string $category, int|string $model) {
        /** @var SceneInterface $scene */
        list($scene, $modelId) = static::scene($article, $category, $model);
        $data = $scene->commentQuery()->where('id', $id)->first();
        if (empty($data)) {
            throw new \Exception('评论不存在');
        }
        $res = self::toggleLog($modelId, SiteRepository::TYPE_COMMENT,
            SiteRepository::ACTION_AGREE, $id,
            [SiteRepository::ACTION_AGREE, SiteRepository::ACTION_DISAGREE]);
        if ($res < 1) {
            $data['agree_count'] --;
            $data['agree_type'] = 0;
        } elseif ($res == 1) {
            $data['agree_count'] ++;
            $data['disagree_count'] --;
            $data['agree_type'] = 1;
        } elseif ($res == 2) {
            $data['agree_count'] ++;
            $data['agree_type'] = 1;
        }
        $scene->commentQuery()->where('id', $id)->update([
           'agree_count' => $data['agree_count'],
           'disagree_count' => $data['disagree_count'],
        ]);
        return $data;
    }

    /**
     * 切换记录
     * @param int $type
     * @param int $action
     * @param int $id
     * @param array|int|null $searchAction
     * @return int {0: 取消，1: 更新为，2：新增}
     * @throws \Exception
     */
    public static function toggleLog(int $modelId, int $type, int $action, int $id, array|int|null $searchAction = null): int {
        if (empty($searchAction)) {
            $searchAction = $action;
        }
        $log = SiteLogModel::where('user_id', auth()->id())
            ->where('model_id', $modelId)
            ->where('item_type', $type)
            ->when(is_array($searchAction), function ($query) use ($searchAction) {
                $query->whereIn('action', $searchAction);
            }, function ($query) use ($searchAction) {
                $query->where('action', $searchAction);
            })
            ->where('item_id', $id)
            ->first();
        if (!empty($log) && $log->action === $action) {
            $log->delete();
            return 0;
        }
        if (!empty($log)) {
            $log->action = $action;
            $log->created_at = time();
            $log->save();
            return 1;
        }
        SiteLogModel::create([
            'model_id' => $modelId,
            'item_type' => $type,
            'item_id' => $id,
            'action' => $action,
            'user_id' => auth()->id()
        ]);
        return 2;
    }

    public static function remove(int $id, int $article, int|string $category, int|string $model): void {
        /** @var SceneInterface $scene */
        list($scene, $_) = static::scene($article, $category, $model);
        $count = $scene->commentQuery()->where('id', $id)->where('user_id', auth()->id())
            ->count();
        if ($count < 1) {
            throw new Exception('操作失败');
        }
        if ($scene->removeComment($id)) {
            $scene->query()->where('id', $article)->updateDecrement('comment_count');
        }
    }

    /**
     * @param int $article
     * @param int|string $category
     * @param int|string $modelId
     * @return array
     * @throws Exception
     */
    public static function scene(int $article, int|string $category, int|string $modelId): array {
        $cat = FuncHelper::channel($category, true);
        FuncHelper::$current['channel'] = $cat['id'];
        FuncHelper::$current['content'] = $article;
        $model = FuncHelper::model($modelId);
        if (empty($model)) {
            throw new Exception('model error');
        }
        FuncHelper::$current['model'] = $model['id'];
        return [CMSRepository::scene()->setModel($model), $model['id']];
    }

    public static function getManageList(int $site, int $article, int|string $category, int|string $model,
                                         string $keywords = '', int $parent_id = 0, int $user = 0,
                                         string $extra = '', int $page = 1, int $prePage = 20)
    {
        SiteRepository::apply($site);
        /** @var SceneInterface $scene */
        list($scene, $modelId) = static::scene($article, $category, $model);
        $data = [
            'parent_id' => $parent_id,
            'content_id' => $article,
            'model_id' => $modelId,
        ];
        if ($user > 0) {
            $data['user_id'] = $user;
        }
        return $scene->searchComment($keywords, $data, 'created_at desc', $extra, $page, $prePage);
    }

    public static function manageRemove(int $site, int $id, int $article, int|string $category, int|string $model)
    {
        SiteRepository::apply($site);
        /** @var SceneInterface $scene */
        list($scene) = static::scene($article, $category, $model);
        if ($scene->removeComment($id)) {
            $scene->query()->where('id', $article)->updateDecrement('comment_count');
        }
    }

}