;
define(["jquery", "chart"], function ($, Chart) {
    var randomColorFactor = function () {
        return Math.round(Math.random() * 255);
    };
    var randomColor = function (opacity) {
        return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
    };
    var ctx = $("#chart");
    var config = {
        type: "line",
        data: {
            labels: new Array,
            datasets: new Array,
            options: {
                title: {
                    display: true,
                    text: ""
                }
            }
        }
    };
    var chart = new Chart(ctx, config);
    var resetConfig = function (data, title) {
        if (title === void 0) { title = ""; }
        config.data = {
            labels: [],
            datasets: [
                {
                    label: "浏览次数（PV）",
                    data: []
                },
                {
                    label: "独立访客（UV）",
                    data: []
                },
                {
                    label: "IP",
                    data: []
                }
            ],
            options: {
                title: {
                    display: true,
                    text: title
                }
            }
        };
        for (var i in data) {
            config.data.labels.push(i);
            var item = data[i];
            config.data.datasets[0].data.push(item[0]);
            config.data.datasets[1].data.push(item[1]);
            config.data.datasets[2].data.push(item[2]);
        }
        $.each(config.data.datasets, function (i, dataset) {
            var background = randomColor(0.5);
            dataset.borderColor = background;
            dataset.backgroundColor = background;
            dataset.pointBorderColor = background;
            dataset.pointBackgroundColor = background;
            dataset.pointBorderWidth = 1;
            dataset.fill = false;
        });
        chart.update();
    };
    var getStatus = function (type) {
        if (type === void 0) { type = 0; }
        $.getJSON('/admin.php/flow/status?type=' + type, function (data) {
            if (data.status != "success") {
                alert("服务器错误！");
            }
            resetConfig(data.data, data.title);
        });
    };
    getStatus();
    $("#status").change(function () {
        getStatus($('option:selected', '#status').index());
    });
});
//# sourceMappingURL=flow.js.map