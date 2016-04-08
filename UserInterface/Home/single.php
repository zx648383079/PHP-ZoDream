<?php
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
$data = $this->get('data');
$links = $this->get('links');
?>

<div class="zd-container">
<div class="ms-Grid off">
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md12">
            <h1 class="text-center"><?php echo $data['title'];?></h1>
            <div>
               作者： <?php echo $data['user'];?>
            </div>
            <div id="content">
                <?php echo $data['content'];?>
            </div>
		</div>
	</div>
    <div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md6">
            <?php if (!empty($links[0])) {?>
            <a class="ms-Link" href="<?php echo $links[0]['id'];?>">
                <i class="ms-Icon ms-Icon--arrowLeft" aria-hidden="true"></i>
                上一篇：<?php echo $links[0]['title'];?>
            </a>
            <?php }?>
		</div>
        <div class="ms-Grid-col ms-u-md6 text-right">
            <?php if (!empty($links[1])) {?>
                <a class="ms-Link" href="<?php echo $links[1]['id'];?>">
                    下一篇：<?php echo $links[1]['title'];?>
                    <i class="ms-Icon ms-Icon--arrowRight" aria-hidden="true"></i>
                </a>
            <?php }?>
		</div>
	</div>
</div>

<div class="ms-Grid">
	<div id="comments" class="ms-Grid-row">
        <h4 class="headTitle">评论</h4>
        <div class="ms-Grid-col ms-u-md12">
            <ul class="ms-List">
                <li v-for="comment in comments" class="ms-ListItem ms-ListItem--image">
                    <div class="ms-ListItem-image" style="background-color: #767676;">&nbsp;</div>
                    <span class="ms-ListItem-primaryText">{{comment.name}}</span>
                    <span class="ms-ListItem-secondaryText"></span>
                    <span class="ms-ListItem-tertiaryText">{{comment.content}}</span>
                    <span class="ms-ListItem-metaText">{{comment.create_at}}</span>
                    <div class="ms-ListItem-selectionTarget js-toggleSelection"></div>
                    <div class="ms-ListItem-actions">
                    <div class="ms-ListItem-action"><i class="ms-Icon ms-Icon--mail"></i></div>
                    </div>
                </li>
              </ul>
            <button class="ms-Button" v-show="more" v-on:click="getMore">加载更多</button>
        </div>

        <?php if ($data['allow_comment'] == 1) {?>
        <div class="ms-Grid-col ms-u-mdPush2 ms-u-md8">
            <form id="comment-form">
                <input type="hidden" name="parent_id" value="0">
                <input type="hidden" name="blog_id" value="<?php echo $data['id'];?>">
                <div class="ms-TextField">
                    <input type="text" name="name" class="ms-TextField-field" placeholder="姓名" value="<?php $this->ech('name');?>" required>
                </div>
                <div class="ms-TextField">
                    <input type="email" name="email" class="ms-TextField-field" placeholder="邮箱" value="<?php $this->ech('email');?>" required>
                </div>
                <div class="ms-TextField ms-TextField--multiline">
                    <textarea name="content" class="ms-TextField-field" placeholder="内容" required></textarea>
                </div>
                <a v-on:click="submit" class="ms-Button">评论</a>
            </form>
        </div>
        <?php }?>
    </div>
</div>
</div>
<?php
$this->extend(array(
    'layout' => array(
        'foot'
    )), array(
        'vue/vue',
        function() {?>
<script type="text/javascript">
//System.import("Vue");
System.import("zodream/zodream");
</script>       
     <?php }
    )
);
?>