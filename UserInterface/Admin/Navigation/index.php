<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->gain('page');
$category = $this->gain('category', array());
?>
<div>
    
    <form method="POST">
        名称： <input type="text" name="name" placeholder="名称" required>
        网址： <input type="text" name="url" placeholder="网址" required>
        分类： <select name="category_id">
            <?php foreach($category as $item) {?>
            <option value="<?php echo $item['id'];?>"><?php echo $item['name'];?></option>
            <?php }?>
        </select>
        顺序： <input type="number" name="position" value="0">
        <button type="submit">增加</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>网址</th>
        <th>分类</th>
        <th>顺序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <form method="POST">
                    <td><?php echo $value['id'];?></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $value['id'];?>">
                        <input type="text" name="name" value="<?php echo $value['name'];?>" placeholder="分类" required>
                    </td>
                    <td>
                        <input type="text" name="url" value="<?php echo $value['url'];?>">
                    </td>
                    <td>
                        <select name="category_id">
                            <?php foreach($category as $item) {?>
                            <option value="<?php echo $item['id'];?>" <?php echo $value['category_id'] == $item['id'] ? 'selected': '';?>><?php echo $item['name'];?></option>
                            <?php }?>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="position" value="<?php echo $value['position'];?>">
                    </td>
                    <td>
                        <button type="submit">修改</button>
                        
                        <a href="#" class="btn">删除</a>
                    </td>
                </form>
            </tr>
        <?php }?>
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