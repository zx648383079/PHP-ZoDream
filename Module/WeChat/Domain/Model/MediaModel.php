<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;


/**
 * 素材存储表
 * @property integer $id
 * @property integer $wid
 * @property string $type
 * @property string $title
 * @property string $content
 * @property integer $parent_id
 * @property string $media_id
 * @property string $result
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
    const MATERIAL_TEMPORARY = 'tomporary';
    /**
     * 永久素材
     */
    const MATERIAL_PERMANENT = 'permanent';
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
     * 素材统称
     * @var array
     */
    public static $mediaTypes = [
        self::TYPE_MEDIA => '媒体素材',
        self::TYPE_NEWS => '图文素材'
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
            'type' => 'required|string:3-10',
            'title' => 'string:3-200',
            'content' => '',
            'parent_id' => 'int',
            'media_id' => 'required|string:3-100',
            'result' => 'required',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'wid' => 'Wid',
            'type' => 'Type',
            'title' => 'Title',
            'content' => 'Content',
            'parent_id' => 'Parent Id',
            'media_id' => 'Media Id',
            'result' => 'Result',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}