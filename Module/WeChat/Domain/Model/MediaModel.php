<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

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
 * @property integer $publish_status
 * @property string $publish_id
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

    const PUBLISH_NONE = 0;
    const PUBLISH_DRAFT = 6;
    const PUBLISH_WAITING = 7;
    const PUBLISH_SUCCESS = 8;

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
            'publish_status' => 'int:0,127',
            'publish_id' => 'string:0,50',
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
            'publish_status' => 'Publish Status',
            'publish_id' => 'Publish Id',
            'expired_at' => 'Expired At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}