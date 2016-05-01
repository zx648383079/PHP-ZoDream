<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$data = $this->get('data', array());
?>
<div>
    <form method="POST">
        名称： <input type="text" name="name" value="" placeholder="名称" required>
        类型： <select name="type">
            <option value="group">分类</option>
            <option selected value="forum">版块</option>
            <option value="sub">子版块</option>
        </select>
        上级： <select name="parent">
            <option value="0">无</option>
            <?php foreach($data as $item) {?>
            <option value="<?php echo $item['id']?>"><?php echo $item['name'];?></option>
            <?php }?>
        </select>
        顺序： <input type="number" name="position" value="0">
        <button type="submit">增加</button>
    </form>
</div>

<table class="table table-hover">
    <thead>
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>类型</th>
        <th>上级</th>
        <th>顺序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($data as $value) {?>
            <tr>
                <form method="POST">
                    <td><?php echo $value['id'];?></td>
                    <td>
                        <input type="hidden" name="id" value="<?php echo $value['id'];?>">
                        <input type="text" name="name" value="<?php echo $value['name'];?>" placeholder="分类" required>
                    </td>
                    <td>
                        <select name="type">
                            <?php $this->swi($value['type'], 'selected');?>
                            <option value="group" <?php $this->cas('group');?>>分类</option>
                            <option value="forum" <?php $this->cas('forum');?>>版块</option>
                            <option value="sub" <?php $this->cas('sub');?>>子版块</option>
                        </select>
                    </td>
                    <td>
                        <select name="parent">
                            <?php $this->swi($value['parent'], 'selected');?>
                            <option value="0" <?php $this->cas(0);?>>无</option>
                            <?php foreach($data as $item) {
                                if ($item['id'] == $value['id']) continue;
                            ?>
                            <option value="<?php echo $item['id'];?>" <?php $this->cas($item['id']);?>><?php echo $item['name'];?></option>
                            <?php }?>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="position" value="<?php echo $value['position'];?>">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">修改</button>
                        <a href="#" class="btn">删除</a>
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