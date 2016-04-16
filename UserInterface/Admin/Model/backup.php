<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>


<div class="panel panel-default">
    <div class="panel-heading">
            <h3 class="panel-title">备份数据库</h3>
    </div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="input_db" class="col-sm-2 control-label">数据库:</label>
                <div class="col-sm-10">
                    <?php foreach($this->get('data', array()) as $item){?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="db[]" value="<?php echo $item;?>">
                            <?php echo $item;?>
                        </label>
                    </div>  
                    <?php }?>
                </div>
            </div>
            
             <div class="form-group">
                <label for="input_file" class="col-sm-2 control-label">保存路径:</label>
                <div class="col-sm-10">
                    <input type="text" name="file" id="input_file" class="form-control" value="" required="required">
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <button type="submit" class="btn btn-primary">备份</button>
                </div>
            </div>
            
            <p class="text-danger"><?php $this->ech('message');?></p>
        </form>
    </div>
</div>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>