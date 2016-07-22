<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Html\Bootstrap\DetailWidget;
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$data = $this->gain('data');
$links = $this->gain('links');
?>


<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title"><?=$data['code']?></h3>
      </div>
      <div class="panel-body">
            <?=DetailWidget::show([
                'data' => $data,
                'items' => [
                    'id' => 'ID',
                    'code' => '编码',
                    'name' => '名称'
                ]
            ])?>
          <div class="row">
              <form method="post">
                  <input type="hidden" name="waste" value="<?=$data['id']?>">
                  <select name="company[]" class="form-control" multiple="multiple" size="20">
                      <?php foreach ($this->gain('company', array()) as $item):?>
                          <option value="<?=$item['id']?>" <?=in_array($item['id'], $links)?'selected':null?>><?=$item['name']?></option>
                      <?php endforeach;?>
                  </select>
                  <button class="btn btn-primary" type="submit">保存</button>
              </form>
              <p><?php $this->out('error');?></p>
          </div>
      </div>
</div>


<?php 
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>