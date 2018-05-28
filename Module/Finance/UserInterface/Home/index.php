<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = '个人财务管理';

$now_total = $now_income + $now_expenditure;
$y_total = $y_income + $y_expenditure;

$tags = [];
$data = [];
foreach ($account_list as $item) {
    $name = "'{$item->name}'";
    $tags[] = $name;
    $data[] = sprintf('{value: %s, name: %s}', $item->total, $name);
}
$data = implode(',', $data);
$tags = implode(',', $tags);

$project_tags = [];
$project_data = [];
foreach ($project_list  as $item) {
    $name = "'{$item->name}'";
    $project_tags[] = $name;
    $project_data[] = '{
    name:'.$name.',
    type:\'line\',
    stack: \'总量\',
    areaStyle: {normal: {}},
    data:['.implode(',', $item->getWeekIncome()).']
}';
}
$project_data = implode(',', $project_data);
$project_tags = implode(',', $project_tags);

$js = <<<JS
   //资产分布
    var myChart = echarts.init(document.getElementById('now-box'));
    var option = {
        title : {
            text: '本月收支情况',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['收入', '支出']
        },
        series : [
            {
                name: '本月收支',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:[
                    {value:{$now_income}, name:'收入'},
                    {value:{$now_expenditure}, name:'支出'},
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
    
    var myChart = echarts.init(document.getElementById('y-box'));
    var option = {
        title : {
            text: '上月收支情况',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: ['收入', '支出']
        },
        series : [
            {
                name: '本月收支',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:[
                    {value:{$y_income}, name:'收入'},
                    {value:{$y_expenditure}, name:'支出'},
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
    
    var myChart = echarts.init(document.getElementById('main'));
    var option = {
        title : {
            text: '资产分布情况',
            x:'center'
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            left: 'left',
            data: [{$tags}]
        },
        series : [
            {
                name: '资产分布',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data:[
                    {$data}
                ],
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);


    //收益曲线
    var myChart1 = echarts.init(document.getElementById('earnings'));
    var option1 = {
        title: {
            text: '收益曲线'
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:[{$project_tags}]
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : ['周一','周二','周三','周四','周五','周六','周日']
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {$project_data}
        ]
    };
    myChart1.setOption(option1);
JS;

$this->registerJsFile('@echarts.min.js')
    ->registerJs($js);
?>

    <div>
        <div class="dashboard-panel panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">本月收支状况</h3 >
            </div>
            <div class="panel-body">
                <p>本月总收支：<?=$now_total?>元
                    <?php if ($now_total > 0) :?>
                    -收入占<?=intval($now_income * 100 / $now_total)?>%，支出占<?=intval($now_expenditure * 100 / $now_total)?>%</p>
                    <?php endif;?>
            </div>
            <div class="panel-body">
                <div id="now-box" style="width: 600px;height:400px;"></div>
            </div>
            <div class="panel-body">
                <p>收入记录：<?=$now_income_count?>条。收入总额：<?=$now_income?>元</p>
                <p>支出记录：<?=$now_expenditure_count?>条。支出总额：<?=$now_expenditure?>元</p>
                <p>余额; 10000元</p>
            </div>
        </div>
        <div class="dashboard-panel panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">上月收支状况</h3 >
            </div>
            <div class="panel-body">
                <p>上月总收支：<?=$y_total?>元
                    <?php if ($y_total > 0) :?>
                    -收入占<?=intval($y_income * 100 / $y_total)?>%，支出占<?=intval($y_expenditure * 100 / $y_total)?>%</p>
                    <?php endif;?>
            </div>
            <div class="panel-body">
                <div id="y-box" style="width: 600px;height:400px;"></div>
            </div>
            <div class="panel-body">
                <p>收入记录：<?=$y_income_count?>条。收入总额：<?=$y_income?>元</p>
                <p>支出记录：<?=$y_expenditure_count?>条。支出总额：<?=$y_expenditure?>元</p>
                <p>余额;10000元</p>
            </div>
        </div>
    </div>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">资产分布</h3 >
            </div>
            <div class="panel-body">
                <div id="main" style="width: 600px;height:400px;"></div>
            </div>
        </div>
    </div>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">收益曲线</h3 >
            </div>
            <div class="panel-body">
                <div id="earnings" style="width: 600px;height:400px;"></div>
            </div>
        </div>
    </div>