<?php
use Zodream\Template\View;
/** @var $this View */

$this->title = '月收支';

$this->extend('layouts/header');
?>

    <div>
        <h2 style="width:60%;">收支明细
            <small>&nbsp;
                <form method="get" id="Pageform" action="" role="form" class="form_inline">
                    <select class="form-control input-sm" style="width:15%;display: inline;" name="month" id="month" >
                        <option value="<?=date("Y-m",strtotime('-4 month'));?>" <?php if(date("Y-m",$month) == date("Y-m",strtotime('-4 month'))){ echo 'selected';}?> ><?=date("Y-m",strtotime('-4 month'));?></option>
                        <option value="<?=date("Y-m",strtotime('-3 month'));?>" <?php if(date("Y-m",$month) == date("Y-m",strtotime('-3 month'))){ echo 'selected';}?> ><?=date("Y-m",strtotime('-3 month'));?></option>
                        <option value="<?=date("Y-m",strtotime('-2 month'));?>" <?php if(date("Y-m",$month) == date("Y-m",strtotime('-2 month'))){ echo 'selected';}?> ><?=date("Y-m",strtotime('-2 month'));?></option>
                        <option value="<?=date("Y-m",strtotime('-1 month'));?>" <?php if(date("Y-m",$month) == date("Y-m",strtotime('-1 month'))){ echo 'selected';}?> ><?=date("Y-m",strtotime('-1 month'));?></option>
                        <option value="<?=date("Y-m");?>" <?php if(date("Y-m",$month) == date("Y-m")){ echo 'selected';}?> ><?=date("Y-m");?></option>
                        <option value="<?=date("Y-m",strtotime('+1 month'));?>" <?php if(date("Y-m",$month) == date("Y-m",strtotime('+1 month'))){ echo 'selected';}?> ><?=date("Y-m",strtotime('+1 month'));?></option>
                        <option value="<?=date("Y-m",strtotime('+2 month'));?>" <?php if(date("Y-m",$month) == date("Y-m",strtotime('+2 month'))){ echo 'selected';}?> ><?=date("Y-m",strtotime('+2 month'));?></option>
                        <option value="<?=date("Y-m",strtotime('+3 month'));?>" <?php if(date("Y-m",$month) == date("Y-m",strtotime('+3 month'))){ echo 'selected';}?> ><?=date("Y-m",strtotime('+3 month'));?></option>
                        <option value="<?=date("Y-m",strtotime('+4 month'));?>" <?php if(date("Y-m",$month) == date("Y-m",strtotime('+4 month'))){ echo 'selected';}?> ><?=date("Y-m",strtotime('+4 month'));?></option>
                    </select>
                </form>
            </small>
        </h2>
        <div style="border:1px solid #e0e0e0;"></div>
        <div class="col-xs-6" style="height:250px;overflow:auto;">
            <!--收入-->
            <h3>月收入明细</h3>
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>时间</td>
                    <td>收入金额</td>
                    <td>备注</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($income as $val){?>
                    <tr>
                        <td><?=date("Y-m-d",strtotime($val['created']));?></td>
                        <td><?=$val['num'];?>元</td>
                        <td><?=$val['desc'];?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>

        </div>
        <div class="col-xs-6" style="height:250px;overflow:auto;">
            <!--支出-->
            <h3>月支出明细&nbsp;<small>支出大于1000红色标识</small></h3>
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>时间</td>
                    <td>支出金额</td>
                    <td>备注</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($expend as $val){?>
                    <tr class="warning">
                        <td><?=date("Y-m-d",strtotime($val['created']));?></td>
                        <td><?=$val['num'];?>元</td>
                        <td><?=$val['desc'];?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <h2>本月收支曲线图&nbsp;<small>截止到当前日期</small></h2>
        <div style="border:1px solid #e0e0e0;"></div>
        <div id="income_payment" style="width: 100%;height:400px;"></div>
    </div>
    <div>
        <h2>最近收支&nbsp;<small><a style="font-size:11px;" href="<?=$this->url('./income/logg')?>">更多</a></small></h2>
        <div style="border:1px solid #e0e0e0;"></div>
        <div class="col-xs-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <td>时间</td>
                    <td>金额</td>
                    <td>备注</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach($lately as $val){?>
                    <tr <?php if($val['type'] ==0){ ?>class="danger"<?php }?>>
                        <td><?=date("Y-m-d",strtotime($val['created']));?></td>
                        <td><?=$val['num'];?>元</td>
                        <td><?=$val['desc'];?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $(function () {
            $("#month").on("change",function (e) {
                $("#Pageform").submit();
            })
        })
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('income_payment'));
        var maxD = new Date(<?=date("Y");?>,<?=date("m");?>,0).getDate();
        var xData = function(){
            var data = [];
            for(var i=1;i<=maxD;i++){
                data.push(i+"日");
            }
            return data;
        }();

        option = {
            "title": {
                "text": "收支环比图",
                "subtext": "收入 vs 支出",
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
                    "data": xData,
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
//            {
//                "name": "收入",
//                "type": "bar",
//                "stack": "总量",
//                "barMaxWidth": 50,
//                "barGap": "10%",
//                "itemStyle": {
//                    "normal": {
//                        "barBorderRadius": 0,
//                        "color": "rgba(60,169,196,0.5)",
//                        "label": {
//                            "show": true,
//                            "textStyle": {
//                                "color": "rgba(0,0,0,1)"
//                            },
//                            "position": "insideTop",
//                            formatter : function(p) {
//                                return p.value > 0 ? (p.value ): '';
//                            }
//                        }
//                    }
//                },
//                "data": [3709, 2417, 755, 2610, 1719, 433, 2544, 4285, 3372, 2484, 4078, 1355, 5208, 17233709, 2417, 755, 2610, 1719, 433, 2544, 4285, 3372, 2484, 4078, 1355, 5208, 17231355, 5208],
//            },
                {
                    "name": "收入",
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
                    "data": <?=$pre_data_s;?>
                },
                {
                    "name": "支出",
                    "type": "bar",
                    "stack": "总量",
                    "itemStyle": {
                        "normal": {
                            "color": "rgba(193,35,43,1)",
                            "barBorderRadius": 0,
                            "label": {
                                "show": true,
                                formatter : function(p) {
                                    return p.value > 0 ? (p.value + '') : '';
                                }
                            }
                        }
                    },
                    "data": <?=$pre_data_z;?>
                }
            ]
        }

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>

<?php
$this->extend('layouts/footer');
?>