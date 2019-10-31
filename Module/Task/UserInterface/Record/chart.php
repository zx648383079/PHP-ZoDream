<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$option = [
    'title' => [
        'text' => '工作统计'
    ],
    'tooltip' => [
        'trigger' => 'axis'
    ],
    'legend' => [
        'data' => []
    ],
    'xAxis' => [
        'type' => 'category',
        'boundaryGap' => false,
        'data' => []
    ],
    'yAxis' => [
        'type' => 'value'
    ],
    'series' => []
];
$maps = [
    'amount' => '估计番茄钟总数',
    'success_amount' => '实际完成番茄钟总数',
    'complete_amount' => '任务完成数',
    'pause_amount' => '中断数',
    'failure_amount' => '中止数',
];
$option['legend']['data'] = array_values($maps);
foreach($day_list as $item) {
    $option['xAxis']['data'][] = $item['day'];
    $i = 0;
    foreach($maps as $k => $label) {
        if (!isset($option['series'][$i])) {
            $option['series'][$i] = [
                'name' => $label,
                'type' => 'line',
                'stack' => '总量',
                'data' => []
            ];
        }
        $option['series'][$i]['data'][] = $item[$k];
        $i ++;
    }
}
$option = json_encode($option);
?>

<div id="main" style="height:400px;"></div>

<script>
    var myChart = echarts.init(document.getElementById('main'));
    myChart.setOption(<?=$option?>);
    $(window).resize(function() {
        myChart.resize();
    });
</script>
