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
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_NEWS = 3;
    const TYPE_TEMPLATE = 4;
    const TYPE_EVENT = 5;
    const TYPE_URL = 6;
    const TYPE_MINI = 7;

    protected $editor = [];

    public function setEditor() {
        $this->type = intval($this->editor['type']);
        if ($this->type == 0) {
            $this->content = $this->editor['text'];
            return $this;
        }
        if ($this->type == 7) {

            return $this;
        }
        if ($this->type == 6) {
            $this->content = $this->editor['url'];
            return $this;
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
        $this->editor['type'] = $this->type;
        if ($this->editor['type'] == 0) {
            $this->editor['text'] = $this->content;
            return;
        }
        if ($this->editor['type'] == 6) {
            $this->editor['url'] = $this->content;
            return;
        }
    }
}