<?php
namespace Module\WeChat\Domain\Model;


use Domain\Model\Model;

/**
 * Class EditorModel
 * @package Module\WeChat\Domain\Model
 * @property integer $type
 * @property string $content
 */
abstract class EditorModel extends Model {

    const TYPE_TEXT = 0;
    const TYPE_MEDIA = 1;
    const TYPE_NEWS = 2;
    const TYPE_TEMPLATE = 3;
    const TYPE_EVENT = 4;
    const TYPE_URL = 5;
    const TYPE_MINI = 6;

    protected $editor = [];

    public static $type_list = [
        self::TYPE_TEXT => '文本',
        self::TYPE_MEDIA => '媒体素材',
        self::TYPE_NEWS => '图文',
        self::TYPE_TEMPLATE => '模板消息',
        self::TYPE_EVENT => '事件',
        self::TYPE_URL => '网址',
        self::TYPE_MINI => '小程序'
    ];

    protected $type_map = [
        self::TYPE_TEXT => [
            'text' => 'content'
        ],
        self::TYPE_MEDIA => [
            'media_id' => 'content'
        ],
        self::TYPE_NEWS => [
            'news_id' => 'content'
        ],
        self::TYPE_TEMPLATE => [
            'template_id' => 'content',
            'template_data' => 'content'
        ],
        self::TYPE_EVENT => [
            'event' => 'content'
        ],
        self::TYPE_URL => [
            'url' => 'content'
        ],
        self::TYPE_MINI => [
            'min_appid' => 'content',
            'min_path' => 'pages'
        ]
    ];

    public function setEditor() {
        $this->type = intval($this->editor['type']);
        foreach ($this->type_map[$this->type] as $key => $item) {
            $this->{$item} = $this->editor[$key];
        }
        return $this;
    }

    public function getEditor($key = null) {
        $this->parseEditor();
        if (empty($key)) {
            return $this->editor;
        }
        if (isset($this->editor[$key])) {
            return $this->editor[$key];
        }
        return null;
    }

    protected function parseEditor() {
        if (!empty($this->editor)) {
            return;
        }
        $this->editor['type'] = intval($this->type);
        foreach ($this->type_map[$this->editor['type']] as $key => $item) {
            $this->editor[$key] = $this->{$item};
        }
    }
}