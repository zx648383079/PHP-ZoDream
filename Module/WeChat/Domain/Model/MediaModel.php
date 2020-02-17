<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;
use Zodream\ThirdParty\WeChat\Media;
use Zodream\ThirdParty\WeChat\NewsItem;
use Exception;

/**
 * 素材存储表
 * @property integer $id
 * @property integer $wid
 * @property string $type
 * @property integer $material_type
 * @property string $title
 * @property string $thumb
 * @property integer $show_cover
 * @property integer $open_comment
 * @property integer $only_comment
 * @property string $content
 * @property integer $parent_id
 * @property string $media_id
 * @property string $url
 * @property integer $expired_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class MediaModel extends Model {
    /**
     * 媒体素材(图片, 音频, 视频, 缩略图)
     */
    const TYPE_MEDIA = 'media';
    /**
     * 图文素材(永久)
     */
    const TYPE_NEWS = 'news';
    /**
     * 图片素材
     */
    const TYPE_IMAGE = 'image';
    /**
     * 音频素材
     */
    const TYPE_VOICE = 'voice';
    /**
     * 视频素材
     */
    const TYPE_VIDEO = 'video';
    /**
     * 缩略图素材
     */
    const TYPE_THUMB = 'thumb';
    /**
     * 临时素材
     */
    const MATERIAL_TEMPORARY = 0;
    /**
     * 永久素材
     */
    const MATERIAL_PERMANENT = 1;
    /**
     * 素材类型
     * @var array
     */
    public static $types = [
        self::TYPE_IMAGE => '图片',
        self::TYPE_THUMB => '缩略图',
        self::TYPE_VOICE => '语音',
        self::TYPE_VIDEO => '视频',
    ];

    /**
     * 素材类别
     * @var array
     */
    public static $materialTypes = [
        self::MATERIAL_TEMPORARY => '临时素材',
        self::MATERIAL_PERMANENT => '永久素材'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'wechat_media';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'type' => 'required|string:0,10',
            'material_type' => 'int:0,9',
            'title' => 'string:0,200',
            'thumb' => 'string:0,200',
            'show_cover' => 'int:0,9',
            'open_comment' => 'int:0,9',
            'only_comment' => 'int:0,9',
            'content' => '',
            'parent_id' => 'int',
            'media_id' => 'string:0,100',
            'url' => 'string:0,255',
            'expired_at' => 'int',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'wid' => 'Wid',
            'type' => '类型',
            'material_type' => '素材类别',
            'title' => '素材标题',
            'thumb' => '封面',
            'show_cover' => '显示封面',
            'open_comment' => '评论',
            'only_comment' => '评论人',
            'content' => 'Content',
            'parent_id' => '主素材',
            'media_id' => 'Media Id',
            'url' => 'Url',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getThumbMediaId(Media $api) {
        $model = static::where('type', self::TYPE_IMAGE)
            ->where('content', $this->thumb)->first();
        if (!$model) {
            $model = new static([
                'title' => $this->title.'-封面',
                'material_type' => self::MATERIAL_PERMANENT,
                'type' => self::TYPE_IMAGE,
                'content' => $this->thumb
            ]);
        }
        if ($model->media_id) {
            return $model->media_id;
        }
        $this->async($api);
        return $model->media_id;
    }

    public function getContent(Media $api) {
        $content = preg_replace('/src=["\']?([^\s"\'>]+)/', function ($match) use ($api) {
            return str_replace($match[1], $this->getImgUrl($api, $match[1]), $match[0]);
        }, $this->content);
        return preg_replace('/url\(["\']?([^\s"\'>)]+)/', function ($match) use ($api) {
            return str_replace($match[1], $this->getImgUrl($api, $match[1]), $match[0]);
        }, $content);
    }

    public function getImgUrl(Media $api, $path) {
        if (strpos($path, 'data:') >= 0) {
            return $path;
        }
        if (strpos($path, 'qlogo.cn') > 0) {
            return $path;
        }
        return $api->uploadImg($path);
    }

    public function async(Media $api) {
        if ($this->media_id &&
            ($this->material_type == self::MATERIAL_PERMANENT || $this->expired_at > time())) {
            return true;
        }
        if ($this->type == self::TYPE_NEWS) {
            return $this->asyncNews($api);
        }
        $file = public_path($this->content);
        if ($this->material_type != self::MATERIAL_PERMANENT) {
            $res = $api->uploadTemp($file, $this->type);
        } else {
            $res = $api->addMedia($file, $this->type);
        }
        if (isset($res['media_id'])) {
            $this->media_id = $res['media_id'];
        }
        if ($res['url']) {
            $this->url = $res['url'];
        }
        return true;
    }

    public function asyncNews(Media $api) {
        if ($this->parent_id > 0) {
            return false;
        }
        $news = new NewsItem();
        $news->setArticle(static::converterNews($this, $api));
        $child = static::where('parent_id', $this->id)->get();
        foreach ($child as $item) {
            $news->setArticle(static::converterNews($item, $api));
        }
        $media_id = $api->addNews($news);
        if (empty($media_id)) {
            return false;
        }
        $this->media_id = $media_id;
        $this->save();
        return true;
    }

    public static function converterNews(MediaModel $model, Media $api) {
        $news = new NewsItem();
        // 处理封面
        $thumb = $model->getThumbMediaId($api);
        if (empty($thumb)) {
            throw new Exception('封面上传失败');
        }
        $news->setThumb($thumb);
        // 处理内容图片路径
        $news->setContent($model->getContent($api));
        return $news->setTitle($model->title)
            ->setShowCover($model->show_cover)
            ->setOnlyFansCanComment($model->only_comment)
            ->setNeedOpenComment($model->open_comment);
    }



}