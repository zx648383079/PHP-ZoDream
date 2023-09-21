<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php if(isset($info)):?>
                    <a href="javascript:void(0);">status:<?=$info['http_code']?></a>
                    <a href="javascript:void(0);">time:<?=$info['total_time']?> s</a>
                <?php else:?>
                响应面板
                <?php endif;?>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">

                <div class="tab-box">
                    <div class="tab-header">
                        <div class="tab-item active">Body</div><div class="tab-item">Headers</div><div class="tab-item">Cookies</div>
                    </div>
                    <div class="tab-body">
                        <div class="tab-item active">
                            <?php if(isset($body)):?>
                                <div class="json-box">
                                </div>
                                <script>
                                $(function () {
                                    refreshJson(<?=$body?>);
                                });
                                </script>
                            <?php else:?>
                            暂无数据
                            <?php endif;?>
                        
                        </div>
                        <div class="tab-item">
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
                        <div class="tab-item">
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