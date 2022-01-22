<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Html\Page;
use Zodream\ThirdParty\WeChat\Media;
use Zodream\ThirdParty\WeChat\NewsItem;

class MediaRepository {

    public static function getList(int $wid, string $keywords = '', string $type = '') {
        AccountRepository::isSelf($wid);
        return MediaModel::where('wid', $wid)
            ->when(!empty($type), function ($query) use ($type) {
                $query->where('type', $type);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')
            ->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return MediaModel::findOrThrow($id, '资源不存在');
    }

    public static function getSelf(int $id) {
        $model = static::get($id);
        AccountRepository::isSelf($model->wid);
        return $model;
    }

    public static function remove(int $id) {
        $model = MediaModel::findOrThrow($id);
        AccountRepository::isSelf($model->wid);
        if ($model->media_id && $model->material_type == MediaModel::MATERIAL_PERMANENT) {
            WeChatModel::find($model->wid)
                ->sdk(Media::class)->deleteMedia($model->media_id);
        }
        $model->delete();
    }

    public static function save(int $wid, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id'], $data['wid']);
        if ($id > 0) {
            $model = static::getSelf($id);
        } else {
            $model = new MediaModel();
            $model->wid = $wid;
            AccountRepository::isSelf($model->wid);
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function async(int $id) {
        $model = MediaModel::find($id);
        AccountRepository::isSelf($model->wid);
        if ($model->media_id &&
            ($model->material_type == MediaModel::MATERIAL_PERMANENT || $model->expired_at > time())) {
            throw new \Exception('不能重复创建');
        }
        /** @var Media $api */
        $api = WeChatModel::findOrThrow($model->wid)
            ->sdk(Media::class);
        if ($model->type === MediaModel::TYPE_NEWS) {
            return static::pushNews($api, $model);
        }
        return static::pushMedia($api, $model);
    }

    /**
     * 上传媒体素材
     * @param Media $api
     * @param MediaModel $model
     * @return bool
     * @throws \Exception
     */
    private static function pushMedia(Media $api, MediaModel $model) {
        $file = static::formatFile($model->content);
        if ($model->material_type != MediaModel::MATERIAL_PERMANENT) {
            $res = $api->uploadTemp($file, $model->type);
        } else {
            $res = $api->addMedia($file, $model->type);
        }
        if (isset($res['errcode']) && !isset($res['media_id'])) {
            throw new \Exception($res['errmsg']);
        }
        if (isset($res['media_id'])) {
            $model->media_id = $res['media_id'];
        }
        if ($res['url']) {
            $model->url = $res['url'];
        }
        $model->save();
        return true;
    }

    /**
     * 转化为本地路径
     * @param string $path
     * @return \Zodream\Disk\File
     */
    private static function formatFile(string $path) {
        return public_path(strpos($path, '://') != false ? parse_url($path, PHP_URL_PATH) : $path);
    }

    /**
     * 上传图文素材
     * @param Media $api
     * @param MediaModel $model
     * @return boolean
     */
    private static function pushNews(Media $api, MediaModel $model) {
        if ($model->parent_id > 0) {
            throw new \Exception('只有主素材才能上传');
        }
        $news = new NewsItem();
        $news->setArticle(static::converterNews($api, $model));
        $child = MediaModel::where('parent_id', $model->id)->get();
        foreach ($child as $item) {
            $news->setArticle(static::converterNews($api, $item));
        }
        if (!$model->media_id) {
            $media_id = $api->addNews($news);
            if (empty($media_id)) {
                throw new \Exception('上传失败');
            }
            $model->media_id = $media_id;
            $model->save();
        } else {
            $news->setMediaId($model->media_id);
            $api->updateNews($news);
        }
        return true;
    }

    private static function converterNews(Media $api, MediaModel $model) {
        $news = new NewsItem();
        // 处理封面
        $thumb = static::getThumbMediaId($api, $model);
        if (empty($thumb)) {
            throw new \Exception('封面上传失败');
        }
        $news->setThumb($thumb);
        // 处理内容图片路径
        $news->setContent(static::renderContent($api, $model));
        return $news->setTitle($model->title)
            ->setShowCover($model->show_cover)
            ->setOnlyFansCanComment($model->only_comment)
            ->setNeedOpenComment($model->open_comment)
            ->setUrl(url('./emulate/media', ['id' => $model->id]));
    }

    private static function getThumbMediaId(Media $api, MediaModel $parent) {
        $model = MediaModel::where('type', MediaModel::TYPE_IMAGE)
            ->where('content', $parent->thumb)->first();
        if (!$model) {
            $model = new MediaModel([
                'title' => $parent->title.'-封面',
                'material_type' => MediaModel::MATERIAL_PERMANENT,
                'type' => MediaModel::TYPE_IMAGE,
                'content' => $parent->thumb
            ]);
        }
        if ($model->media_id) {
            return $model->media_id;
        }
        static::pushMedia($api, $model);
        return $model->media_id;
    }

    private static function renderContent(Media $api, MediaModel $model): string {
        $content = preg_replace_callback('/src=["\']?([^\s"\'>]+)/', function ($match) use ($api) {
            return str_replace($match[1], $this->getImgUrl($api, $match[1]), $match[0]);
        }, $model->content);
        return preg_replace_callback('/url\(["\']?([^\s"\'>)]+)/', function ($match) use ($api) {
            return str_replace($match[1], $this->getImgUrl($api, $match[1]), $match[0]);
        }, $content);
    }

    /**
     * 转化为微信接受的图片链接
     * @param Media $api
     * @param $path
     * @return bool|mixed|string
     * @throws \Exception
     */
    private static function getImgUrl(Media $api, $path) {
        if (strpos($path, 'data:') >= 0) {
            return $path;
        }
        if (strpos($path, 'qlogo.cn') > 0) {
            return $path;
        }
        if (strpos($path, 'qpic.cn') > 0) {
            return $path;
        }
        return $api->uploadImg(static::formatFile($path));
    }

    public static function search(int $wid, string $keywords = '', string $type = '', int|array $id = 0) {
        AccountRepository::isSelf($wid);
        return MediaModel::where('wid', $wid)
            ->when(!empty($type), function ($query) use ($type) {
                $query->where('type', $type);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->when(!empty($id), function ($query) use ($id) {
                if (is_array($id)) {
                    $query->whereIn('id', $id);
                    return;
                }
                $query->where('id', $id);
            })->select('id', 'title', 'type', 'thumb', 'created_at')->page();
    }

    /**
     * 拉取全部素材
     * @param int $wid
     * @return void
     */
    public static function pull(int $wid, string $type = '') {
        set_time_limit(0);
        /** @var Media $api */
        $api = WeChatModel::findOrThrow($wid)
            ->sdk(Media::class);
        // $res = $api->materialCount();
        if (!empty($type)) {
            static::pullMedia($wid, $api, $type);
            return;
        }
        static::pullMedia($wid, $api, Media::IMAGE);
        static::pullMedia($wid, $api, Media::VIDEO);
        static::pullMedia($wid, $api, Media::VOICE);
        static::pullMedia($wid, $api, Media::NEWS);
    }

    public static function pullMedia(int $wid, Media $api, string $type = Media::IMAGE) {
        $total = 0;
        $offset = 0;
        $size = 20;
        do {
            $res = $api->mediaList($type, $offset, $size);
            if (isset($res['errcode'])) {
                throw new \Exception($res['errmsg']);
            }
            $total = $res['total_count'];
            foreach ($res['item'] as $item) {
                $model = MediaModel::where('media_id', $item['media_id'])->where('wid', $wid)
                ->first();
                if (empty($model)) {
                    $model = new MediaModel([
                       'wid' => $wid,
                       'type' => $type,
                       'media_id' => $item['media_id']
                    ]);
                }
                $model->material_type = MediaModel::MATERIAL_PERMANENT;
                if ($type !== Media::NEWS) {
                    $model->content = $model->url = $item['url'];
                    $model->title = $item['name'];
                    $model->save();
                    continue;
                }
                $first = $item['content']['news_item'][0];
                if (!$model->getIsNewRecord()) {
                    static::pullANews($first, $model);
                    $model->save();
                    continue;
                }
                static::pullANews($first, $model);
                $model->save();
                for ($i = 1; $i < count($item['content']['news_item']); $i ++) {
                    $next = new MediaModel([
                        'wid' => $wid,
                        'type' => $type,
                        'parent_id' => $model->id,
                    ]);
                    static::pullANews($item['content']['news_item'][$i], $next);
                    $next->save();
                }
            }
            $offset += $size;
        } while ($total > $offset + 1);
    }

    private static function pullANews(array $data, MediaModel $model) {
        $model->title = $data['title'];
        $model->url = $data['url'];
        $model->content = $data['content'];
        $model->show_cover = $data['show_cover_pic'];
        if (!empty($data['thumb_media_id'])) {
            $model->thumb = MediaModel::where('media_id', $data['thumb_media_id'])
                ->where('wid', $model->wid)
                ->value('content');
        }
    }
}