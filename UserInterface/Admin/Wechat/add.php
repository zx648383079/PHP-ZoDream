<?php
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>

<div class="container">
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">添加公众号</h3>
          </div>
          <div class="panel-body">
                <form method="POST" class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="input_name" class="col-sm-2 control-label">*公众号名称:</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" id="input_name"
                                   class="form-control" value="<?php $this->out('data.name');?>" required="required">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="input_original_id" class="col-sm-2 control-label">*公众号原始Id:</label>
                        <div class="col-sm-10">
                            <input type="text" name="original_id"
                                   id="input_original_id" class="form-control"
                                   value="<?php $this->out('data.original_id');?>" required="required">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="input_account" class="col-sm-2 control-label">*微信号:</label>
                        <div class="col-sm-10">
                            <input type="text"
                                   name="account" id="input_account"
                                   class="form-control"
                                   value="<?php $this->out('data.account');?>" required="required">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_token" class="col-sm-2 control-label">*Token:</label>
                        <div class="col-sm-10">
                            <input type="text" name="token" id="input_token"
                                   class="form-control"
                                   value="<?php $this->out('data.token');?>" required="required">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_aes_key" class="col-sm-2 control-label">Aes Key:</label>
                        <div class="col-sm-10">
                            <input type="text" name="aes_key"
                                   id="input_aes_key" class="form-control"
                                   value="<?php $this->out('data.aes_key');?>">
                        </div>
                    </div>
                    
                    
                    
                    <div class="form-group">
                        <label for="input_app_id" class="col-sm-2 control-label">AppID（公众号）:</label>
                        <div class="col-sm-10">
                            <input type="text" name="app_id"
                                   id="input_app_id" class="form-control"
                                   value="<?php $this->out('data.app_id');?>">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_app_secret" class="col-sm-2 control-label">AppSecret:</label>
                        <div class="col-sm-10">
                            <input type="text" name="app_secret"
                                   id="input_app_secret"
                                   class="form-control"
                                   value="<?php $this->out('data.app_secret');?>">
                        </div>
                    </div>
                    
                    
                    
                    <div class="form-group">
                        <label for="input_type" class="col-sm-2 control-label">微信号类型:</label>
                        <div class="col-sm-10">
                            <select name="type" id="input_type" class="form-control" >
                                <?php $this->swi($this->gain('data.type'));?>
                                <option value="0" <?php $this->cas(0)?>>订阅号</option>
                                <option value="1" <?php $this->cas(1)?>>服务号</option>
                                <option value="2" <?php $this->cas(2)?>>企业号</option>
                            </select>
                        </div>
                    </div>
                    
                        
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">保存</button>
                        </div>
                    </div>
                </form>
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