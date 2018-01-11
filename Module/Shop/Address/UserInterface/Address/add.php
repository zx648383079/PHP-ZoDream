<?php
defined('APP_DIR') or exit();
use Zodream\Html\Bootstrap\FormWidget;
/** @var $this \Zodream\Template\View */
$this->title = '';
$this->extend('layout/header');
?>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">增加</h3>
	</div>
	<div class="panel-body">
		<?=FormWidget::begin($model)
		->hidden('id')
        ->text('id', ['label' => 'id', 'required' => true])
        ->text('user_id', ['label' => 'user_id', 'required' => true])
        ->text('name', ['label' => 'name'])
        ->text('tel', ['label' => 'tel'])
        ->text('region_id', ['label' => 'region_id'])
        ->text('address', ['label' => 'address'])
        ->text('status', ['label' => 'status'])
        ->text('create_at', ['label' => 'create_at'])
        ->text('update_at', ['label' => 'update_at'])
		->button()
		->end();
		?>
		<p><?=$model->getFirstError()?></p>
	</div>
</div>


<?php$this->extend('layout/footer');
?>