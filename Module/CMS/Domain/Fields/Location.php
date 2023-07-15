<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Template\View;

class Location extends BaseField {
    
    static bool $isInit = false;

    public function options(ModelFieldModel $field, bool $isJson = false): string|array {
        if ($isJson) {
            return [];
        }
        return '';
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->string()->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'location',
                'value' => $value
            ];
        }
        $html = $this->getDialog();
        return <<<HTML
<div class="input-group">
    <label for="{$field['field']}">{$field['name']}</label>
    <div>
       <input type="text" id="{$field['field']}" name="{$field['field']}" value="{$value}">
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
var target;
var selected = [116.331398,39.897445];
var map;
function initMap() {
    if (map) {
        return;
    }
    map = new BMapGL.Map("allmap");            
	var point = new BMapGL.Point(selected[0], selected[1]);
	map.centerAndZoom(point,12);
    map.enableScrollWheelZoom(true);

	var geolocation = new BMapGL.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			var mk = new BMapGL.Marker(r.point);
			map.addOverlay(mk);
			map.panTo(r.point);
			selected = [r.point.lng, r.point.lat]
		}     
	},{enableHighAccuracy: true})        
	//单击获取点击的经纬度
	map.addEventListener("click",function(e){
        map.clearOverlays();
        var mk = new BMapGL.Marker(e.latlng);
        map.addOverlay(mk);
        selected = [e.latlng.lng, e.latlng.lat];
    });
}
$('[data-type="location"]').on('click', function() {
    locationDialog.show();
    target = $(this).prev();
    initMap();
    var val = target.val();
    if (!val || val.indexOf(',') < 0) {
        return;
    }
    selected = val.split(',');
    map.clearOverlays();
    point = new BMapGL.Point(selected[0], selected[1]);
    var mk = new BMapGL.Marker(point);
    map.addOverlay(mk);
    map.centerAndZoom(point, 12);
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
    map.centerAndZoom(val, 11);
}).on('keydown', 'input', function (e) {
    if (e.code == 'Enter') {
        e.preventDefault();
        var val = $(this).val();
        if (!val) {
            return;
        }
        map.centerAndZoom(val, 11);
    }
});
JS;
        $apiKey = config('thirdparty.baidu.map');
        view()->registerJsFile('//api.map.baidu.com/api?v=1.0&type=webgl&ak='.$apiKey)
            ->registerJs($js, View::JQUERY_READY);
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
        <div id="allmap" style="width: 450px; height:340px;"></div>
    </div>
    <div class="dialog-footer"><button type="button" class="dialog-yes">确认</button><button type="button"
            class="dialog-close">取消</button></div>
</div>
HTML;

    }
}