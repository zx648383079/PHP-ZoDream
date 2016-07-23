<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->gain('page');
$category = $this->gain('category', array());
?>
<div class="row">
    <form method="POST">
        <h4 class="col-xs-1">
            名称：
        </h4>
        <div class="col-xs-2">
        <input type="text" class="form-control" name="name" placeholder="名称" required>
            </div>
        <h4 class="col-xs-1">
            网址：
        </h4>
        <div class="col-xs-2">
         <input type="text" class="form-control" name="url" placeholder="网址" required>
            </div>
        <h4 class="col-xs-1">
            分类：
        </h4>
        <div class="col-xs-2">
        <select class="form-control" name="category_id">
            <?php foreach($category as $item) :?>
            <option value="<?= $item['id']?>"><?=$item['name']?></option>
            <?php endforeach;?>
        </select>
        </div>
        <h4 class="col-xs-1">
            顺序：
        </h4>
        <div class="col-xs-1">
        <input type="number" class="form-control" name="position" value="0">
            </div>
        <div class="col-xs-1">
            <button type="submit" class="btn btn-primary">增加</button>
        </div>
    </form>
</div>

<table class="table table-hover">
    <thead>
    <tr>
        <th>顺序</th>
        <th>ID</th>
        <th>名称</th>
        <th>网址</th>
        <th>分类</th>

        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) :?>
            <tr>
                <form method="POST">
                    <td>
                        <input type="number" class="form-control" name="position" value="<?=$value['position'];?>">
                    </td>
                    <td><?=$value['id'];?></td>
                    <td>
                        <input type="hidden" name="id" value="<?=$value['id'];?>">
                        <input type="text" class="form-control" name="name" value="<?=$value['name'];?>" placeholder="分类" required>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="url" value="<?=$value['url'];?>">
                    </td>
                    <td>
                        <select class="form-control" name="category_id">
                            <?php foreach($category as $item) :?>
                            <option value="<?=$item['id'];?>" <?=$value['category_id'] == $item['id'] ? 'selected': '';?>><?php echo $item['name'];?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">修改</button>
                        <a href="#" class="btn btn-danger">删除</a>
                    </td>
                </form>
            </tr>
        <?php endforeach;?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="3">
                <?php $page->pageLink();?>
            </th>
        </tr>
    </tfoot>
</table>

<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>