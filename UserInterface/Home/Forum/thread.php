<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Authentication\Auth;
/** @var $this \Zodream\Domain\Response\View */
/** @var $page \Zodream\Domain\Html\Page */
$this->extend(array(
	'layout' => array(
		'head',
        'navbar'
	)), array(
        'zodream/blog.css'
    )
);
$sub = $this->get('sub', array());
$page = $this->get('page');
?>
<div class="container">
    <?php if (!empty($sub)) {?>
    <div>
        <div>子版块</div>
        <?php foreach ($sub as $item) { ?>
            <div>
                <div><?php echo $item['name'];?></div>
            </div>
        <?php }?>
    </div>
    <?php }?>
   
   
   <div class="row">
       <div class="col-md-2">
           <a href="<?php $this->url('forum/add');?>" class="btn btn-primary">发帖</a>
       </div>
       
   </div>
    
    
    <div class="row">
        
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
    </div>
    
    <?php if (!Auth::guest()) {?>
    <div class="panel panel-default">
          <div class="panel-heading">
                <h3 class="panel-title">快速发帖</h3>
          </div>
          <div class="panel-body">
                <form method="POST" class="form-horizontal" role="form">
                    <input type="hidden" name="forum_id" value="<?php $this->ech('id');?>">
                    
                    <div class="form-group">
                        <label for="input_title" class="col-sm-2 control-label">标题:</label>
                        <div class="col-sm-10">
                            <input type="text" name="title" id="input_title" class="form-control" value="" required="required" >
                        </div>
                    </div>
                    
                    
                    <div class="form-group">
                        <label for="textarea_content" class="col-sm-2 control-label">内容:</label>
                        <div class="col-sm-10">
                            <textarea name="content" id="textarea_content" class="form-control" rows="3" required="required"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">发表</button>
                        </div>
                    </div>
                </form>
          </div>
    </div>
    <?php }?>
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	))
);
?>