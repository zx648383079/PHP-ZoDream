<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\DetailWidget;
/** @var $this \Zodream\Domain\View\View */
$this->title = '';
$this->extend('layout/header');
?>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?=$data['id']?></h3>
	</div>
	<div class="panel-body">
        <?=DetailWidget::show([
                'data' => $model,
				'items' => [
                'id' => 'Id',
                'name' => 'Name',
                'slug' => 'Slug',
                'group' => 'Group',
            ]
		])?>
	</div>
</div>


<?php$this->extend('layout/footer');
?>