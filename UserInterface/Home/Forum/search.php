<?php
defined('APP_DIR') or exit();
use Zodream\Service\Routing\Url;
/** @var $this \Zodream\Template\View */
/** @var $page \Zodream\Html\Page */
$this->title = $title;
$this->registerCssFile('zodream/blog.css');
$this->extend([
    'layout/header',
    'layout/navbar'
]);
?>
<div class="container">
    
    <div class="row">
        <?php if (!empty($page->getPage())) :?>
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
                <?php foreach ($page->getPage() as $item) :?>
                    <tr>
                        <td class="thread">
                            <a href="<?=Url::to(['forum/post', 'id' => $item['id']]);?>">
                                <?=$item['title'];?>
                            </a>
                        </td>
                        <td>
                            <?=$item['user_name'];?></br>
                            <?=$this->ago($item['create_at']);?>
                        </td>
                        <td>
                            <?=$item['replies'];?> </br>
                            <?=$item['views'];?>
                        </td>
                        <td>
                            <?= $item['update_user'];?></br>
                            <?=$this->ago($item['update_at']);?>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5">
                        <?php $page->pageLink();?>
                    </th>
                </tr>
            </tfoot>
        </table>
        <?php else:?>
        <h3 class="text-center text-danger">查询无结果！</h3>
        <?php endif;?>
    </div>
</div>


<?php $this->extend('layout/footer')?>