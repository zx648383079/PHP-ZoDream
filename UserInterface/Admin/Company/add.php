<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\FormWidget;
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
    'layout' => array(
        'head'
    )), array(
        'zodream/add.css'
    )
);
?>


<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title">增加公司</h3>
      </div>
      <div class="panel-body">
            <?=FormWidget::begin($this->gain('data'))
             ->hidden('id')
            ->text('name', ['label' => '公司名', 'required' => true])
            ->textArea('description', ['label' => '介绍'])
            ->text('charge', ['label' => '负责人', 'required' => true])
            ->text('phone', ['label' => '联系方式', 'required' => true])
            ->button()
            ->end();
            ?>
          <p><?php $this->out('error');?></p>
      </div>
</div>


<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        function(){?>
<script type="text/javascript">
require(['admin/add']);
</script>
			<?php }
        )
);
?>