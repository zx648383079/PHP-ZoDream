<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '编辑商品';
$js = <<<JS
    var ue = UE.getEditor('container');
JS;

$this->registerJsFile('/assets/ueditor/ueditor.config.js')
    ->registerJsFile('/assets/ueditor/ueditor.all.js')
    ->registerJs($js);
?>

    <h1><?=$this->title?></h1>
    <form data-type="ajax" action="<?=$this->url('./admin/goods/save')?>" method="post" class="form-table" role="form">
        <div class="zd-tab">
            <div class="zd-tab-head">
                <div class="zd-tab-item active">
                    基本
                </div>
                <div class="zd-tab-item">
                    详情
                </div>
            </div>
            <div class="zd-tab-body">
                <div class="zd-tab-item active">
                    <div class="input-group">
                        <label>商品名</label>
                        <input name="name" type="text" class="form-control"  placeholder="输入商品名" value="<?=$model->name?>">
                    </div>
                    <div class="input-group">
                        <label>货号</label>
                        <input name="sign" type="text" class="form-control"  placeholder="输入货号" value="<?=$model->sign?>">
                    </div>
                    <div class="input-group">
                        <label>分类</label>
                        <select name="cat_id" required>
                            <option value="">请选择</option>
                            <?php foreach($cat_list as $item):?>
                            <option value="<?=$item->id?>" <?=$item->id == $model->cat_id ? 'selected' : ''?>><?=$item->name?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>品牌</label>
                        <select name="brand_id" required>
                            <option value="">请选择</option>
                            <?php foreach($brand_list as $item):?>
                            <option value="<?=$item->id?>" <?=$item->id == $model->brand_id ? 'selected' : ''?>><?=$item->name?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>价格</label>
                        <input name="price" type="text" class="form-control"  placeholder="输入价格" value="<?=$model->price?>">
                    </div>
                    <div class="input-group">
                        <label>市场价</label>
                        <input name="market_price" type="text" class="form-control"  placeholder="输入市场价" value="<?=$model->market_price?>">
                    </div>
                    <div class="input-group">
                        <label>库存</label>
                        <input name="stock" type="text" class="form-control"  placeholder="输入库存" value="<?=$model->stock?>">
                    </div>
                    <div class="input-group">
                        <label>关键词</label>
                        <textarea name="keywords" class="form-control" placeholder="关键词"><?=$model->keywords?></textarea>
                    </div>
                    <div class="input-group">
                        <label>简介</label>
                        <textarea name="description" class="form-control" placeholder="简介"><?=$model->description?></textarea>
                    </div>
                    <div class="input-group">
                        <label>
                            <input value="1" name="is_best" type="checkbox" <?=$model->is_best || $model->id < 1 ? 'checked': ''?>> 精品
                        </label>
                    </div>
                    <div class="input-group">
                        <label>
                            <input value="1" name="is_hot" type="checkbox" <?=$model->is_hot || $model->id < 1 ? 'checked': ''?>> 热门
                        </label>
                    </div>
                    <div class="input-group">
                        <label>
                            <input value="1" name="is_new" type="checkbox" <?=$model->is_new || $model->id < 1 ? 'checked': ''?>> 最新
                        </label>
                    </div>
                    <div class="input-group">
                        <label for="image">主图</label>
                        <div class="file-input">
                            <input type="text" id="image" name="thumb" placeholder="请输入主图" value="<?=$model->thumb?>">
                            <button type="button" data-type="upload" data-grid="image">上传</button>
                            <button type="button" data-type="preview">预览</button>
                        </div>
                    </div>
                </div>
                <div class="zd-tab-item">
                    <script id="container" style="height: 400px" name="content" type="text/plain" required>
                        <?=$model->content?>
                    </script>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-success">确认保存</button>
        <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
        <input type="hidden" name="id" value="<?=$model->id?>">
    </form>
