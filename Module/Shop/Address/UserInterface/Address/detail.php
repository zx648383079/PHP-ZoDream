<?php
defined('APP_DIR') or exit();
use Zodream\Html\Bootstrap\DetailWidget;
/** @var $this \Zodream\Template\View */
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
                'user_id' => 'User Id',
                'name' => 'Name',
                'tel' => 'Tel',
                'region_id' => 'Region Id',
                'address' => 'Address',
                'status' => 'Status',
                'create_at' => 'Create At',
                'update_at' => 'Update At',
            ]
		])?>
	</div>
</div>


<?php$this->extend('layout/footer');
?>