<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>

<div>
    增加作者:
    <form>
        作者名称:<input type="text" name="name" value="">
        联系邮箱:<input type="text" name="url" value="mailto:">
        <button type="submit">增加</button>
        <button type="reset">重置</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>作者</th>
        <th>操作</th>
    </tr>
    </thead>
     <tbody>
        <?php foreach($page->getPage() as $item) {?>
            <tr>
                <form>
                    <td>
                       作者名称: <input type="text" name="name" value="<?php echo $item['writer'];?>">
                        联系邮箱: <input type="text" name="url" value="<?php echo $item['email'];?>">
                    </td>
                    <td>
                        <button type="submit">修改</button>
                        <button>删除</button>
                    </td>
                </form>
            </tr>
        <?php }?>
        </tbody>
        <tfoot>
        <tr>
            <th colspan="2">
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