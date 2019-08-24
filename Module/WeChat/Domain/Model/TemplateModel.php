<?php
namespace Module\WeChat\Domain\Model;

use Domain\Model\Model;

/**
 * Class TemplateModel
 * @package Module\WeChat\Domain\Model
 * @property integer $id
 * @property integer $wid
 * @property string $template_id
 * @property string $title
 * @property string $content
 * @property string $example
 */
class TemplateModel extends Model {


    public static function tableName() {
        return 'wechat_template';
    }

    protected function rules() {
        return [
            'wid' => 'required|int',
            'template_id' => 'required|string:0,64',
            'title' => 'required|string:0,100',
            'content' => 'required|string:0,255',
            'example' => 'required|string:0,255',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'wid' => 'Wid',
            'template_id' => 'Template Id',
            'title' => 'Title',
            'content' => 'Content',
            'example' => 'Example',
        ];
    }

    public function getFields() {
        if (preg_match_all('/\{([^\{]+)\.DATA\}/', $this->content, $matches, PREG_SET_ORDER)) {
            return array_column($matches, 1);
        }
        return [];
    }

    public function preview($data) {
        $data = self::strToArr($data);
        return preg_replace_callback('/\{\s?\{([^\{]+)\.DATA\}\s?\}/', function ($match) use ($data) {
            $key = trim($match[1]);
            return isset($data[$key]) ? $data[$key] : '';
        }, $this->content);
    }

    public static function strToArr($data) {
        if (is_array($data)) {
            return $data;
        }
        $args = [];
        foreach (explode("\n", $data) as $line) {
            if (empty($line)) {
                continue;
            }
            $arg = explode('=', $line, 2);
            if (empty($arg[0])) {
                continue;
            }
            $args[$arg[0]] = isset($arg[1]) ? $arg[1] : '';
        }
        return $args;
    }

}