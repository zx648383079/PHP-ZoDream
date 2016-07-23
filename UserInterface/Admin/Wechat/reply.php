<?php
defined('APP_DIR') or exit();
use Zodream\Domain\Routing\Url;
use Zodream\Infrastructure\Html;
/** @var $this \Zodream\Domain\View\Engine\DreamEngine */
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
echo \Zodream\Domain\Html\Bootstrap\PanelWidget::show(array(
    'head' => Html::tag('a', '增加', array(
        'class' => 'btn btn-primary',
        'href' => Url::to('wechat/addReply')
    )),
    'body' => \Zodream\Domain\Html\Bootstrap\TableWidget::show(array(
        'page' => $this->gain('page'),
        'columns' => array(
            'id' => 'ID',
            'type' => array(
                'label' => '回复类型',
                'format' => array(
                    'follow' => '关注回复',
                    'default' => '默认回复',
                    'keywords' => '关键词回复'
                )
            ),
            'name' => '名称',
            'update_at' => array(
                'label' => '更新时间',
                'format' => 'datetime'
            ),
            array(
                'key' => 'id',
                'label' => '操作',
                'format' => function($id) {
                    return Html::tag('a', '编辑', array(
                        'href' => Url::to('wechat/addReply/id/'.$id)
                    ));
                }
            )
        )
    ))
));
?>


<?php
$this->extend(array(
    'layout' => array(
        'foot'
    ))
);
?>
