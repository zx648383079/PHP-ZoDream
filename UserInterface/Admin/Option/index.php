<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<div class="row">
    <form method="POST">
        <h4 class="col-xs-1">名称： </h4>
        <div  class="col-xs-3">
            <input type="text" class="form-control" name="name" placeholder="名称" required>
        </div>
        <h4 class="col-xs-1">值： </h4>
        <div  class="col-xs-3">
         <textarea name="value" class="form-control" rows="1" cols="30" placeholder="值" required></textarea>
        </div>
            <h5 class="col-xs-1">自动加载： </h5>
        <div  class="col-xs-2">
         <input type="text" class="form-control" name="autoload" placeholder="自动加载" value="yes">
        </div>
        <div class="col-xs-1">
            <button type="submit" class="btn btn-primary">增加</button>
        </div>
    </form>
</div>


<table class="table table-hover">
    <thead>
    <tr>
        <th>名称</th>
        <th>值</th>
        <th>自动加载</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->gain('data', array()) as $value) {?>
            <tr>
                <form method="POST">
                    <td>
                        <input type="text" class="form-control" name="name" value="<?php echo $value['name'];?>" placeholder="名称" required>
                    </td>
                    <td>
                        <textarea name="value" class="form-control" rows="1" cols="30"><?php echo $value['value'];?></textarea>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="autoload" placeholder="自动加载" value="<?php echo $value['autoload'];?>">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">修改</button>
                        <a href="#" class="btn btn-danger">删除</a>
                    </td>
                </form>
            </tr>
        <?php }?>
    </tbody>
</table>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>