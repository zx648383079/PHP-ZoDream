<?php
use Zodream\Html\Bootstrap\TabWidget;
echo TabWidget::show([
    'items' => [
        [
            'active' => true,
            'title' => '文字',
            'content' => <<<HTML
<div>
<textarea class="form-control" rows="5"></textarea>
</div>
HTML
        ],
        [
            'title' => '图片',
            'content' => <<<HTML
<div class="row">
<div class="col-md-6">
上传
</div>

<div class="col-md-6">
选择
</div>
</div>
HTML
        ],
        [
            'title' => '语音',
            'content' => <<<HTML
<div class="row">
<div class="col-md-6">
上传
</div>

<div class="col-md-6">
选择
</div>
</div>
HTML
        ],
        [
            'title' => '视频',
            'content' => <<<HTML
<div class="row">
<div class="col-md-6">
上传
</div>

<div class="col-md-6">
选择
</div>
</div>
HTML
        ],
        [
            'title' => '图文消息',
            'content' => <<<HTML
<div class="row">
<div class="col-md-6">
新增
</div>

<div class="col-md-6">
选择
</div>
</div>
HTML

        ]
    ]
]);
