<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = 'ZoDream Log Viewer';
$columns = [
    's_sitename' => '服务名称',
    's_computername' => '服务器名称',
    's_ip' => '服务器IP',
    'cs_method' => '方法',
    'cs_uri_stem' => 'URL资源',
    'cs_uri_query' => 'URL查询',
    's_port' => '服务器端口',
    'cs_username' => '用户名',
    'c_ip' => '客户端IP',
    'cs_user_agent' => '用户代理',
    'cs_version' => '协议版本',
    'cs_referer' => '引用网站',
    'cs_cookie' => 'Cookie',
    'cs_host' => '主机',
    'sc_status' => '协议状态',
    'sc_substatus' => '协议子状态',
    'sc_win32_status' => 'win32状态',
    'sc_bytes' => '发送的字节数',
    'cs_bytes' => '接收的字节数',
    'time_taken' => '所用时间',
];
$log_str = implode(',', $data);
$time_str = implode(',', array_keys($data));
$tag = $columns[$name];
$js = <<<JS
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('log_count'));

        option = {
            "title": {
                "text": "{$tag} 统计曲线图",
                "subtext": "{$tag}",
                "x": "center"
            },
            "tooltip": {
                "trigger": "axis",
                "axisPointer": {
                    "type": "shadow"
                },
            },
            "grid": {
                "borderWidth": 0,
                "y2": 120
            },
            "legend": {
                "x": "right",
                "data": [ ]
            },
            "toolbox": {
                "show": true,
                "feature": {
                    "restore": { },
                    "saveAsImage": { }
                }
            },
            "calculable": true,
            "xAxis": [
                {
                    "type": "category",
                    "splitLine": {
                        "show": false
                    },
                    "axisTick": {
                        "show": false
                    },
                    "splitArea": {
                        "show": false
                    },
                    "axisLabel": {
                        "interval": 0,
                        "rotate": 45,
                        "show": true,
                        "splitNumber": 15,
                        "textStyle": {
                            "fontFamily": "微软雅黑",
                            "fontSize": 12
                        }
                    },
                    "data": [$time_str],
                }
            ],
            "yAxis": [
                {
                    "type": "value",
                    "splitLine": {
                        "show": false
                    },
                    "axisLine": {
                        "show": true
                    },
                    "axisTick": {
                        "show": false
                    },
                    "splitArea": {
                        "show": false
                    }
                }
            ],
            "dataZoom": [
                {
                    "show": true,
                    "height": 30,
                    "xAxisIndex": [
                        0
                    ],
                    bottom:40,
                    "start": 0,
                    "end": 80
                },
                {
                    "type": "inside",
                    "show": true,
                    "height": 15,
                    "xAxisIndex": [
                        0
                    ],
                    "start": 1,
                    "end": 35
                }
            ],
            "series": [
                {
                    "name": "{$tag}",
                    "type": "line",
                    "data": [{$log_str}]
                },
               
            ]
        }

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
JS;

$this->registerJsFile('@echarts.min.js')
    ->registerJs($js);
?>

<div class="page-search-bar">
    <form class="form-horizontal" role="form">
        <div class="input-group">
            <select name="name">
            <?php foreach($columns as $key => $item):?>
                <option value="<?=$key?>" <?=$key == $name ? 'selected' : ''?>><?=$item?></option>
            <?php endforeach;?>
            </select>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" name="value" value="<?=$value?>">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
</div>

<div>
    <h2> <?=$tag?> 统计曲线图&nbsp;</h2>
    <div style="border:1px solid #e0e0e0;"></div>
    <div id="log_count" style="width: 100%;height:400px;"></div>
</div>