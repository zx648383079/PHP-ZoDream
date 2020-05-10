<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Module\WeChat\Domain\Model\MenuModel;
/** @var $this View */
$this->title = '仿真模拟器';
function wxMenuItem(MenuModel $item) {
    if ($item->type == MenuModel::TYPE_URL) {
        return sprintf('<li><a href="%s" target="_blank">%s</a></li>', $item->content, $item->name);
    }
    if ($item->type == MenuModel::TYPE_MINI) {
        return sprintf('<li><a href="%s" target="_blank">%s</a></li>', $item->content, $item->name);
    }
    return sprintf('<li data-event="%s">%s</li>', $item->content, $item->name);
}
$js = <<<JS
bindEmulate({$wx->id});
JS;
$this->registerJs($js);
?>
<div class="emulate-box">
    <div class="box-header">
        <i class="fa fa-arrow-left"></i>
        <?=$wx->name?>
        <div class="header-right">
            <i class="fa fa-ellipsis-v"></i>
            <div class="sub-box">
                <div class="qr">
                    <img src="<?=$wx->qrcode ?: $this->asset('images/wx.jpg')?>" alt="<?=$wx->name?>">
                </div>
                <div class="name"><?=$wx->name?></div>
                <div class="desc"><?=$wx->description?></div>
            </div>
        </div>
    </div>
    <div class="scroll-body">
        <?php foreach($news_list as $item):?>
        <p class="time-item"><?=$this->ago($item->getAttributeSource('created_at'))?></p>
        <a class="new-item" href="<?=$this->url('./emulate/media', ['id' => $item->id])?>" target="_blank">
            <div class="thumb">
                <img src="<?=$item->thumb?>" alt="<?= $item->title ?>">
            </div>
            <div class="title">
                <?= $item->title ?>
            </div>
        </a>
        <?php endforeach;?>
    </div>
    <div class="box-footer<?= empty($menu_list) ? ' toggle-input' : '' ?>">
        <div class="input-box">
            <div>
                <i class="fa fa-list"></i>
            </div>
            <textarea></textarea>
            <div>
                <i class="fa fa-plus-circle"></i>
            </div>
        </div>
        <div class="input-more-box">
            <div class="icon-item">
                <i class="fa fa-image"></i>
                图片
            </div>
            <div class="icon-item">
                <i class="fa fa-file-audio"></i>
                语音
            </div>
            <div class="icon-item">
                <i class="fa fa-map-pin"></i>
                地址
            </div>
        </div>
        <div class="menu-box">
            <div>
                <i class="fa fa-keyboard"></i>
            </div>
            <ul class="menu-body">
                <?php foreach($menu_list as $item):?>
                <?php if($item->children):?>
                <li class="menu-item">
                    <span><?=$item->name?></span>
                    <ul>
                        <?php foreach($item->children as $child):?>
                            <?= wxMenuItem($child)?>
                        <?php endforeach;?>
                    </ul>
                </li>
                <?php else:?>
                <?= wxMenuItem($item)?>
                <?php endif;?>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>