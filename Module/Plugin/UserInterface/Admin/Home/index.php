<?php
defined('APP_DIR') or exit();

use Zodream\Template\View;
/** @var $this View */

$this->title = '插件管理';
$js = <<<JS
$(document).on('click', 'a[data-type=auto]', function(e) {
    e.preventDefault();
    var that = \$(this);
    var loading = Dialog.loading();
    var url = that.attr('href');
    postJson(url, function(data) {
        loading.close();
        if (data.code != 200 || !data.data.form) {
            parseAjax(data);
            return;
        }
        Dialog.form(data.data.form, that.data('title') ?? '快速配置')
        .on('done', function() {
            ajaxForm(url, this.data, res => {
                if (res.code == 200) {
                    this.close();
                }
                parseAjax(res);
            });
        });
    });
});
JS;
$this->registerJs($js, View::JQUERY_READY);
?>

<div class="scroll-panel page-multiple-table page-multiple-enable">
    <div class="panel-body">
        <?php foreach($items as $item):?>
        <div class="panel-swiper-item">
            <div class="swiper-header page-multiple-td" data-id="<?=$item['id']?>">
                <i class="checkbox"></i>
            </div>
            <div class="swiper-body">
                <div class="item-header">
                    <span class="item-name"><?= $item['name'] ?></span>
                    <span class="item-author"><?= $item['author'] ?></span>
                    <div class="item-version"><?= $item['version'] ?>
                        <i class="iconfont icon-arrow-up" title="有更新"></i>
                    </div>
                </div>
                <div class="item-body">
                    <?= $item['description'] ?>
                </div>
            </div>
            <div class="swiper-action">
                <?php if($item['status'] > 0):?>
                <a class="btn-primary no-jax" href="<?=$this->url('./@admin/home/execute', ['id' => $item['id']])?>">运行</a>
                <a class="btn-danger no-jax" href="<?=$this->url('./@admin/home/uninstall', ['id' => $item['id']])?>" data-type="del" data-tip="确认卸载此插件？">卸载</a>
                <?php else:?>
                <a class="btn-primary no-jax" href="<?=$this->url('./@admin/home/install', ['id' => $item['id']])?>" data-type="auto">安装</a>
                <?php endif;?>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <div class="panel-footer">
        <div class="panel-action page-multiple-th">
            <i class="checkbox"></i>
            <div class="btn-group">
                <a class="btn btn-danger no-jax" href="<?=$this->url('./@admin/home/uninstall', ['id' => 0])?>" data-type="del" data-tip="确认卸载选中插件？">卸载选中项(
                    <span class="page-multiple-count">0</span>
                    )</a>
                <a href="<?=$this->url('./@admin/home/sync')?>" data-type="ajax" class="btn btn-info no-jax">更新</a>
            </div>
        </div>
        <?=$items->getLink()?>
    </div>
</div>