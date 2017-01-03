<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
$this->extend('layout/header');
?>


<div class="panel panel-default">
    <div class="panel-heading">
            <h3 class="panel-title">执行数据库语句</h3>
    </div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal" role="form">
             <div class="form-group">
                <textarea name="sql" class="form-control" rows="5" required="required"></textarea>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">执行</button>
            </div>
        </form>
        <p class="text-danger"><?=$message?></p>
        <?php 
        if (!empty($data)) :
            if (is_array($data)) :
                if (!is_array(current($data))) :
                    $data = array($data);
                endif;?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <?php foreach ($data[0] as $key => $item) :?>
                                <th><?=$key;?></th>
                            <?php endforeach;?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item) :?>
                         <tr>
                            <?php foreach ($item as $value) :?>
                                <td><?=$value;?></td>
                            <?php endforeach;?>
                        </tr>
                        <?php endforeach;?>
                        
                    </tbody>
                </table>
                
      <?php else:
                echo '<p class="text-danger">影响 ',$data,' 行</p>';
           endif;
        endif;?>
    </div>
</div>

<?=$this->extend('layout/footer')?>