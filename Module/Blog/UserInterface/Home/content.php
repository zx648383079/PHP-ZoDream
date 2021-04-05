<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Infrastructure\HtmlExpand;
use Infrastructure\Player;
/** @var $this View */
$html = $blog->toHtml();
?>
<?php if($blog->can_read):?>
    <?php if(isset($metaItems['audio_url']) && !empty($metaItems['audio_url'])):?>
        <?= Player::player($this, $metaItems['audio_url'], 'audio') ?>
    <?php endif;?>
    <?php if(isset($metaItems['video_url']) && !empty($metaItems['video_url'])):?>

        <?= Player::player($this, $metaItems['video_url']) ?>
    <?php endif;?>
   <?=$html?>
<?php else:?>
   <?=HtmlExpand::substr($html, 50)?>
   <?php if($blog->open_type == 1):?>
      <div class="rule-box rule-login">
            <div class="rule-tip">
                文章必须 
                <a href="<?=$this->url('/auth', ['redirect_uri' => $this->url()])?>">登录</a>
                才能继续阅读
            </div>
      </div>
   <?php elseif ($blog->open_type == 5):?>
    <div class="rule-box rule-password">
        <div class="rule-header">
            请输入阅读密码
        </div>
        <input type="text" name="password" required placeholder="请输入密码">
        <button data-url="<?=$this->url('./home/open', ['id' => $blog->id], false)?>">确认</button>
    </div>
    <?php elseif ($blog->open_type == 6):?>
    <div class="rule-box rule-buy">
        <div class="rule-header">
            文章必须支付 <b><?=__('price format', ['money' => $blog->open_rule])?></b> 才能继续阅读
        </div>
        <button data-url="<?=$this->url('./home/open', ['id' => $blog->id], false)?>">确认购买</button>
    </div>
   <?php endif;?>
<?php endif;?>
