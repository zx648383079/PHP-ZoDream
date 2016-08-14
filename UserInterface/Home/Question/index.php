<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Url\Url;
/** @var $this \Zodream\Domain\View\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->title = $title;
$this->registerCssFile('zodream/blog.css');
$this->extend([
    'layout/head',
    'layout/navbar'
]);
?>
<div class="container">
    
   <div class="row">
       <div class="col-md-2">
           <a href="<?=Url::to('question/add');?>" class="btn btn-primary">提问</a>
       </div>
       
   </div>
    
    
    <div class="row">
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>问题</th>
                    <th>状态</th>
                    <th>提问者</th>
                    <th>回复</th>
                    <th>发布时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($page->getPage() as $item) :?>
                    <tr>
                        <td class="thread">
                            <a href="<?=Url::to(['question/view','id' => $item['id']]);?>">
                                <?=$item['title'];?>
                            </a>
                        </td>
                        <td>
                            <?=$item['status'];?></br>
                        </td>
                        <td>
                            <?=$item['user'];?>
                        </td>
                        <td>
                            <?=$item['count'];?>
                        </td>
                        <td>
                            <?=$this->ago($item['create_at']);?>
                        </td>
                    </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4">
                        <?php $page->pageLink();?>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php $this->extend('layout/foot')?>