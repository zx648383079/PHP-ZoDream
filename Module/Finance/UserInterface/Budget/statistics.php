<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->name. ' 预算';
$cycle_list = ['次', '天', '周', '月', '年'];
$tip_str = implode(',', array_keys($log_list));
$log_str = implode(',', $log_list);
$budget_str = str_repeat($model->budget .',', count($log_list));
$js = <<<JS
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('log_grid'));

        option = {
            "title": {
                "text": "预算支出环比图",
                "subtext": "预算",
                "x": "center"
            },
            "tooltip": {
                "trigger": "axis",
                "axisPointer": {
                    "type": "shadow"
                },
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
                    "data": [{$tip_str}],
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
                show: false,
                start: 0,
                end: 100
            ],
            "series": [
                {
                    "name": "支出",
                    "type": "bar",
                    "stack": "总量",
                    "barMaxWidth": 50,
                    "itemStyle": {
                        "normal": {
                            "color": "rgba(51,204,112,1)",
                            "barBorderRadius": 0,
                            "label": {
                                "show": true,
                                formatter : function(p) {
                                    return p.value > 0 ? (p.value + '') : '';
                                }
                            }
                        }
                    },
                    "data": [{$log_str}]
                },
                {
                    name:'预算',
                    type:'line',
                    data: [$budget_str]
                }
            ]
        }

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
JS;

$this->extend('layouts/header')
    ->registerJsFile('@echarts.min.js')
    ->registerJs($js);
?>

    <div>
        <h2>预算支出曲线图&nbsp;<small>按 <?=$cycle_list[$model->cycle]?> 周期统计</small></h2>
        <div>
            总支出：<?=$sum?>，总预算：<?=$budget_sum?>，超出：<?=$sum > $budget_sum ? $sum - $budget_sum : 0?>
        </div>
        <div style="border:1px solid #e0e0e0;"></div>
        <div id="log_grid" style="width: 100%;height:400px;"></div>
    </div>
    

<?php
$this->extend('layouts/footer');
?>