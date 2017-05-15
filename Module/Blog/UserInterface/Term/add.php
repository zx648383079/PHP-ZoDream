<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\FormWidget;
/** @var $this \Zodream\Domain\View\View */
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
        ->text('name', ['label' => 'name', 'required' => true])
        ->text('slug', ['label' => 'slug'])
        ->text('group', ['label' => 'group'])
		->button()
		->end();
		?>
		<p><?=$model->getFirstError()?></p>
	</div>
</div>


<?php$this->extend('layout/footer');
?>