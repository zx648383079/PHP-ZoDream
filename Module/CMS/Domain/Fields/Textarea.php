<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Html\Dark\Theme;

class Textarea extends BaseField {

    public function options(ModelFieldModel $field)
    {

        return implode('', [
            Theme::text('setting[option][width]', '', '宽度'),
            Theme::text('setting[option][height]', '', '高度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], 0, '移动端自动宽度'),
            Theme::text('setting[option][value]', '', '默认值'),
            Theme::select('setting[option][type]', ['char', 'varchar', 'text'], 0, '字段类型'),
            Theme::text('setting[option][length]', '', '字段长度'),
        ]);
    }

    public function add($name, $options)
    {
        // TODO: Implement add() method.
    }

    public function edit($name, $options)
    {
        // TODO: Implement edit() method.
    }

    public function drop($name)
    {
        // TODO: Implement drop() method.
    }

    public function set(ModelFieldModel $field)
    {
        // TODO: Implement set() method.
    }

    public function get($name, ContentModel $model)
    {
        // TODO: Implement get() method.
    }

    public function input()
    {
        // TODO: Implement input() method.
    }

    public function output($value)
    {
        // TODO: Implement output() method.
    }
}