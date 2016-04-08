<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>

<form>
    站点名称:<input type="text" name="name" value="">
    链接地址:<input type="text" name="url" value="http://">
    <button type="submit">增加</button>
    <button type="reset">重置</button>
</form>

<table>
    <thead>
    <tr>
        <th>信息来源</th>
        <th>操作</th>
    </tr>
    </thead>
     <tbody>
        <?php foreach($page->getPage() as $item) {?>
            <tr>
                <form>
                    <td>
                        站点名称:<input type="text" name="name" value="<?php echo $item['sitename'];?>">
                        链接地址:<input type="text" name="url" value="<?php echo $item['siteurl'];?>">
                    </td>
                    <td>
                        <button type="submit">修改</button>
                        <button type="reset">删除</button>
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