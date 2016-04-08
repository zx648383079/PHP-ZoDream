<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
?>
<button>增加字段</button>
<button>管理会员表单</button>
<table>
    <thead>
    <tr>
        <th>顺序</th>
        <th>字段名</th>
        <th>字段标识</th>
        <th>字段类型</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($this->get('data', array()) as $value) {?>
            <tr>
                <td><input type="text" name="" value="<?php echo $value['myorder'];?>"></td>
                <td><?php echo $value['f'];?></td>
                <td><?php echo $value['fname'];?></td>
                <td><?php echo $value['ftype'];?></td>
                <td>[修改][删除]</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th colspan="4"><button>修改字段顺序</button> (值越小越前面)</th>
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