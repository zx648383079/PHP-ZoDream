<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\View */
/** @var $model \Domain\Model\Home\FriendLinkModel  */
$this->extend('layout/head');
?>


<div class="panel panel-default">
      <div class="panel-heading">
            <h3 class="panel-title">编辑友情链接</h3>
      </div>
      <div class="panel-body">
            <form method="POST" class="form-horizontal" role="form">
                    <input type="hidden" name="id" value="<?=$model->id?>">
                    <div class="form-group">
                        <label for="input_name" class="col-sm-2 control-label">名称：</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" id="input_name" class="form-control" value="<?php $this->out('data.name');?>" placeholder="名称" required>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_url" class="col-sm-2 control-label">网址：</label>
                        <div class="col-sm-10">
                            <input type="text" name="url" id="input_url" class="form-control" value="<?=$model->url?>" required placeholder="网址">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="textarea_description" class="col-sm-2 control-label">说明：</label>
                        <div class="col-sm-10">
                            <textarea name="description" id="textarea_description" class="form-control" rows="3"><?=$model->description?></textarea>
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_logo" class="col-sm-2 control-label">Logo：</label>
                        <div class="col-sm-10">
                            <input type="text" name="logo" id="input_logo" class="form-control" value="<?=$model->logo?>" placeholder="LOGO">
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="input_position" class="col-sm-2 control-label">顺序：</label>
                        <div class="col-sm-10">
                            <input type="number" name="position" id="input_position" class="form-control" value="<?=$model->position?>" >
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

<?=$this->extend('layout/foot')?>