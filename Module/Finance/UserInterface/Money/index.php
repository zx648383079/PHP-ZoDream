<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '总资金';

$tags = [];
$data = [];
foreach ($project_list as $item) {
    $name = "'{$item->name}'";
    $tags[] = $name;
    $data[] = sprintf('{value: %s, name: %s}', $item->money, $name);
}
$data = implode(',', $data);
$tags = implode(',', $tags);

$js = <<<JS
   var myChart = echarts.init(document.getElementById('main'));
    var option = {
        title : {
            text: '资产分布',
            subtext: '以录入系统数据为准',
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
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        series : [
            {
                name: '资金分布',
                type: 'pie',
                radius : '55%',
                center: ['50%', '60%'],
                data: [
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
JS;


$this->extend('layouts/header')
    ->registerJsFile('@echarts.min.js')
    ->registerJs($js);
?>

    <div>
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>总资金</th>
                    <th>账户</th>
                    <th>可用资金</th>
                    <th>冻结资金</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($account_list as $key => $item): ?>
                    <tr>
                        <?php if($key < 1):?>
                            <td rowspan="<?=count($account_list)?>" align="center"><?=$total?></td>
                        <?php endif?>
                        <td><?=$item->name?></td>
                        <td><?=$item->money?></td>
                        <td><?=$item->frozen_money?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div style="margin: 0 0 20px 2px;">
        <h3>理财产品</h3>
        <?php foreach($product_list as $item):?>
            <div class="col-lg-6">
                <h4><?=$item->name?></h4>
                <p><?=$item->money?>元</p>
            </div>
        <?php endforeach;?>
    </div>
    <div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">理财项目分布</h3 >
            </div>
            <div class="panel-body">
                <div id="main" style="width: 600px;height:400px;"></div>
            </div>
        </div>
    </div>

<?php
$this->extend('layouts/footer');
?>