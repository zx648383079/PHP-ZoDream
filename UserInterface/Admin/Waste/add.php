<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\FormWidget;
/** @var $this \Zodream\Domain\View\View */
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
            <h3 class="panel-title">增加规格</h3>
      </div>
      <div class="panel-body">
            <?=FormWidget::begin($this->gain('data'))
             ->hidden('id')
            ->text('code', ['label' => '编号', 'required' => true])
            ->text('name', ['label' => '名称', 'required' => true])
            ->textArea('content', ['label' => '介绍', 'required' => true])
            ->textArea('damage', ['label' => '危害'])
            ->textArea('treatment', ['label' => '处理方法'])
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