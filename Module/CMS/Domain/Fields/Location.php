<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Zodream\Database\Contracts\Column;
use Zodream\Template\View;

class Location extends BaseField {
    
    static bool $isInit = false;

    public function options(bool $isJson = false): string|array {
        if ($isJson) {
            return [];
        }
        return '';
    }



    public function alterColumn(Column $column): void {
        $column->string()->default('')->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'location',
                'value' => $value
            ];
        }
        $html = $this->getDialog();
        return <<<HTML
<div class="input-group">
    <label for="{$this->controlName()}">{$this->controlLabel()}</label>
    <div class="file-input">
       <input type="text" id="{$this->controlName()}" class="form-control" name="{$this->controlName()}" value="{$value}">
       <button type="button" class="btn btn-primary" data-type="location">拾取</button>
    </div>
</div>
{$html}
HTML;
    }
    
    
    public function getDialog(): string {
        if (static::$isInit) {
            return '';
        }
        static::$isInit = true;
        $js = <<<JS
var locationDialog = $('#show-location-box').dialog();
var mapFrame = $('#map-frame');
mapFrame.on('load', function() {
    mapFrame.contentWindow.map_builder.on('click', function (e) {
        this.clear().mark(e.x, e.y);
    });
});
var target;
$('[data-type="location"]').on('click', function() {
    locationDialog.show();
    target = $(this).prev();
    var val = target.val();
    if (!val || val.indexOf(',') < 0) {
        return;
    }
    selected = val.split(',');
    mapFrame.contentWindow.map_builder.clear()
        .mark(selected[0], selected[1]);
});
locationDialog.on('done', function () {
    target.val(selected.join(','));
    this.close();
});
locationDialog.find('.search-box').on('click', 'button', function (e) {
    e.preventDefault();
    var val = $(this).prev().val();
    if (!val) {
        return;
    }
    mapFrame.contentWindow.map_builder.search(val);
}).on('keydown', 'input', function (e) {
    if (e.code == 'Enter') {
        e.preventDefault();
        var val = $(this).val();
        if (!val) {
            return;
        }
        mapFrame.contentWindow.map_builder.search(val);
    }
});
JS;
        view()->registerJs($js, View::JQUERY_READY);
        return <<<HTML
<div id="show-location-box" class="dialog dialog-box" data-type="dialog" style="z-index:1200;">
    <div class="dialog-header">
        <div class="dialog-title">坐标拾取</div><i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body" style="width: 500px; height:400px;">
        <div class="search-box">
            城市名: <input type="text" class="form-control" id="city-box"/>
            <button type="button" class="btn btn-primary">查询</button>
        </div>
        <iframe id="map-frame" src="/home/map" style="width: 450px; height:340px;"></iframe>
    </div>
    <div class="dialog-footer"><button type="button" class="dialog-yes">确认</button><button type="button" class="dialog-close">取消</button></div>
</div>
HTML;

    }
}