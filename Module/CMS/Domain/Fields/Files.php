<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Support\MessageBag;

class Files extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
            'allow' => File::DEFAULT_ALLOW,
            'size' => '2M',
            'count' => '*',
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'allow',
                    'label' => '允许格式',
                    'type' => 'text',
                    'value' => $option['allow'],
                ],
                [
                    'name' => 'size',
                    'label' => '允许单个大小',
                    'type' => 'text',
                    'value' => $option['size'],
                ],
                [
                    'name' => 'count',
                    'label' => '允许数量',
                    'type' => 'text',
                    'value' => $option['count'],
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][allow]', $option['allow'], '允许格式'),
            Theme::text('setting[option][size]', $option['size'], '允许单个大小'),
            Theme::text('setting[option][count]', $option['count'], '允许数量'),
        ]);
    }



    public function alterColumn(Column $column): void {
        $column->text()->nullable()->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'files',
                'value' => $value
            ];
        }

        $items = static::fromMultipleText($value);
        $html = '';
        foreach ($items as $item) {
            $name = self::getFileName($item);
            $html .= <<<HTML
<div class="upload-item">
    <span class="item-body">{$name}</span>
    <span class="item-close">&times;</span>
    <input type="hidden" name="{$this->controlName()}[]" value="{$item}">
</div>
HTML;
        }
        return <<<HTML
<div class="input-group">
    <label for="{$this->controlName()}">{$this->controlLabel()}</label>
     <div class="multiple-upload-panel" data-type="files">
        <div class="panel-header">
            <input type="text" name="____{$this->controlName()}" placeholder="回车/ENTER 添加文件链接" class="form-control">
            <a class="btn btn-default add-item">上传</a>
        </div>
        <div class="panel-body">
            {$html}
        </div>
    </div>
</div>
HTML;
    }

    public function filterInput(mixed $value, MessageBag $bag): mixed {
        $value = static::toMultipleText($value);
        if ($this->isRequired() && empty($value)) {
            $bag->add($this->controlName(), sprintf('[%s]%s', $this->controlLabel(),
                !empty($this->field['error_message']) ? $this->field['error_message'] : '必填项'));
        }
        return $value;
    }

    public function toText(mixed $value): string {
        $items = static::fromMultipleText($value);
        $html = '';
        foreach ($items as $item) {
            $html .= <<<HTML
<p><a href="{$item}" download>下载</a></p>
HTML;
        }
        return $html;
    }

    public function formatValue(mixed $value): mixed {
        return static::fromMultipleText($value);
    }

    public static function getFileName(string $value): string {
        $i = strpos($value, '?');
        if ($i === false) {
            $j = strrpos($value, '/');
            return $j === false ? $value : substr($value, $j + 1);
        }
        $j = strrpos($value, '&');
        if ($j === false) {
            return substr($value, $i + 1);
        }
        return substr($value, $j + 1);
    }
}