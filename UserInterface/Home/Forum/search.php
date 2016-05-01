<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Authentication\Auth;
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	)), array(
        'zodream/blog.css'
    )
);
$page = $this->get('page');
?>
<div class="container">
    
    <div class="row">
        <?php if (!empty($page->getPage())) {?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>主题</th>
                    <th>作者</th>
                    <th>回复/查看</th>
                    <th>最后发表</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($page->getPage() as $item) {?>
                    <tr>
                        <td class="thread">
                            <a href="<?php $this->url('forum/post/id/'.$item['id']);?>">
                                <?php echo $item['title'];?>
                            </a>
                        </td>
                        <td>
                            <?php echo $item['user_name'];?></br>
                            <?php $this->ago($item['create_at']);?>
                        </td>
                        <td>
                            <?php echo $item['replies'];?> </br>
                            <?php echo $item['views'];?>
                        </td>
                        <td>
                            <?php echo $item['update_user'];?></br>
                            <?php $this->ago($item['update_at']);?>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">
                        <?php $page->pageLink();?>
                    </th>
                </tr>
            </tfoot>
        </table>
        <?php } else {?>
        <h3 class="text-center text-danger">查询无结果！</h3>
        <?php }?>
    </div>
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>