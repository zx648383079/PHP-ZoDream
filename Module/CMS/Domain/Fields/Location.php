<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Template\View;

class Location extends BaseField {
    
    static $isInit = false;

    public function options(ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [];
        }
        return '';
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->string()->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field, bool $isJson = false) {
        if ($isJson) {
            return [
                'name' => $field->field,
                'label' => $field->name,
                'type' => 'location',
                'value' => $value
            ];
        }
        $html = $this->getDialog();
        return <<<HTML
<div class="input-group">
    <label for="{$field->field}">{$field->name}</label>
    <div>
       <input type="text" id="{$field->field}" name="{$field->field}" value="{$value}">
       <button type="button" data-type="location">拾取</button>
    </div>
</div>
{$html}
HTML;
    }
    
    
    public function getDialog() {
        if (static::$isInit) {
            return '';
        }
        static::$isInit = true;
        $js = <<<JS
var locationDialog = $('#show-location-box').dialog();
var target;
var selected = [116.331398,39.897445];
var map = new BMap.Map("allmap");            
	var point = new BMap.Point(selected[0], selected[1]);
	map.centerAndZoom(point,12);

	var geolocation = new BMap.Geolocation();
	geolocation.getCurrentPosition(function(r){
		if(this.getStatus() == BMAP_STATUS_SUCCESS){
			var mk = new BMap.Marker(r.point);
			map.addOverlay(mk);
			map.panTo(r.point);
			selected = [r.point.lng, r.point.lat]
		}     
	},{enableHighAccuracy: true})        
	//单击获取点击的经纬度
	map.addEventListener("click",function(e){
        var mk = new BMap.Marker(e.point);
        map.addOverlay(mk);
        selected = [e.point.lng, e.point.lat];
    });
$('[data-type="location"]').click(function() {
    locationDialog.show();
    target = $(this).prev();
    var val = target.val();
    if (!val || val.indexOf(',') < 0) {
        return;
    }
    selected = val.split(',');
    point = new BMap.Point(selected[0], selected[1]);
    var mk = new BMap.Marker(point);
    map.addOverlay(mk);
    map.panTo(point);
});
locationDialog.on('done', function () {
    target.val(selected.join(','));
    this.close();
});
locationDialog.find('.search-box button').click(function () {
    var val = $(this).prev().val();
    if (!val || val == '') {
        return;
    }
    map.centerAndZoom(val, 11);
});
JS;
        view()->registerJsFile('http://api.map.baidu.com/api?v=2.0&ak=ngBpWunS4vnarcr0l4S3sSkXscssGqnw')
            ->registerJs($js, View::JQUERY_READY);
        return <<<HTML
<div id="show-location-box" class="dialog dialog-box" data-type="dialog" style="z-index:1000;">
    <div class="dialog-header">
        <div class="dialog-title">坐标拾取</div><i class="fa fa-close dialog-close"></i>
    </div>
    <div class="dialog-body" style="width: 500px; height:400px;">
        <div class="search-box">
            城市名: <input type="text" id="city-box"/>
            <button type="button">查询</button>
        </div>
        <div id="allmap"></div>
    </div>
    <div class="dialog-footer"><button type="button" class="dialog-yes">确认</button><button type="button"
            class="dialog-close">取消</button></div>
</div>
HTML;

    }
}