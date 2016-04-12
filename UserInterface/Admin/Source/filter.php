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
    增加过滤字符:
    <form>
        将新闻内容包含 
        <textarea name="oldword" cols="45" rows="5"></textarea>
        替换成 
        <textarea name="newword" cols="45" rows="5"></textarea> 
        <button type="submit">增加</button>
        <button type="reset">重置</button>
    </form>
</div>

<table>
    <thead>
    <tr>
        <th>过滤字符</th>
        <th>操作</th>
    </tr>
    </thead>
     <tbody>
        <?php foreach($page->getPage() as $item) {?>
            <tr>
                <form>
                    <td>
                       将新闻内容包含 
                        <textarea name="oldword" cols="45" rows="5"><?php echo $item['oldword'];?></textarea>
                        替换成 
                        <textarea name="newword" cols="45" rows="5"><?php echo $item['newword'];?></textarea> 
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