<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php if(isset($info)):?>
                <a href="javascript:void(0);">status:<?=$info['status_code']?></a>
                <a href="javascript:void(0);">time:<?=$info['total_time']?> s</a>
                <?php else:?>
                响应面板
                <?php endif;?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="zd-tab">
                    <div class="zd-tab-head">
                        <div class="zd-tab-item active">Body</div><div class="zd-tab-item">Headers</div><div class="zd-tab-item">Cookies</div>
                    </div>
                    <div class="zd-tab-body">
                        <div class="zd-tab-item active">
                            <?php if(isset($body)):?>
                            <div class="json-box"><?=$body?></div>
                            <?php else:?>
                            暂无数据
                            <?php endif;?>
                        
                        </div>
                        <div class="zd-tab-item">
                            <?php if(isset($headers)):?>
                            <?php if(isset($headers['request'])):?>
                            <h5>request</h5>
                            <?php foreach($headers['request'] as $item):?>
                            <p>
                                <?=$item?>
                            </p>
                            <?php endforeach;?>
                            <?php endif;?>
                            <?php if(isset($headers['response'])):?>
                            <h5>response</h5>
                            <?php foreach($headers['response'] as $item):?>
                            <p>
                                <?=$item?>
                            </p>
                            <?php endforeach;?>
                            <?php endif;?>

                            <?php else:?>
                            暂无数据
                            <?php endif;?>
                        </div>
                        <div class="zd-tab-item">
                            <p>
                                暂无数据
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
</div>