<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */

$this->title = $model->title;
$this->set([
    'keywords' => $model->title,
    'description' => $model->description
]);
?>
<div class="container">
    <div class="agreement-box">
        <div class="update-date">
            <?=__('Release Date:')?>
            <span class="date-value"><?= $model->created_at ?></span>
        </div>
        <div class="update-date">
            <?=__('Effective date:')?>
            <span class="date-value"><?= $model->updated_at ?></span>
        </div>

        <div class="print-bar">
            <div class="print-btn" onclick="window.print()">
                <i class="fa fa-print"></i>
                <?=__('Print')?>
            </div>
        </div>

        <div class="title" id="top"><?= $model->title ?></div>

        <div class="row">
            <div class="col-md-12">
                <?= $model->description ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <ul class="nav-list">
                    <?php foreach($model->content as $item):?>
                    <li>
                        <a href="#<?= $item['name'] ?>"><?= $item['title'] ?></a>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="col-md-9">
                <?php foreach($model->content as $group):?>
                <div id="<?= $group['name'] ?>" class="nav-panel">
                    <div class="nav-header">
                    <?= $group['title'] ?>
                    </div>
                    <div class="nav-desc">
                        <?php foreach($group['children'] as $item):?>
                        <p class="<?= $item['b'] ? 'b' : '' ?>"> <?=$item['content']?> </p>
                        <?php endforeach;?>
                    </div>
                </div>
                <?php endforeach;?>
                <a class="goto-top" href="#top" title="<?=__('Back to top')?>">
                    <i class="fa fa-arrow-up"></i>
                    <?=__('Back to top')?>
                </a>
            </div>
        </div>

    </div>
</div>