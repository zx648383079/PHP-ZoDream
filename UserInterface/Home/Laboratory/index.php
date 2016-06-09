<?php
defined('APP_DIR') or exit();
/** @var $this \Zodream\Domain\Response\View */
$this->extend(array(
    'layout' => array(
        'head',
        'navbar'
    ))
);
?>

<div class="container">
    <div class="row">
        <h1>此为实验室，所有功能处于试验阶段，不建议正式发布！</h1>
    </div>
    <div class="row lab">
        <div class="col-md-3">
            <div>
                <a href="<?php $this->url('navigation');?>">导航</a>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <a href="<?php $this->url('forum');?>">论坛</a>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <a href="<?php $this->url('chat');?>">聊天室</a>
            </div>
        </div>
        <div class="col-md-3">
            <div>
                <a href="<?php $this->url('waste');?>">废料科普</a>
            </div>
        </div>

        <div class="col-md-3">
            <div>
                <a href="<?php $this->url('question');?>">问答</a>
            </div>
        </div>

        <div class="col-md-3">
            <div>
                <a href="<?php $this->url('company');?>">公司供求</a>
            </div>
        </div>
    </div>
</div>

<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>