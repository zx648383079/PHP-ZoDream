<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
use Zodream\Html\Dark\Form;
/** @var $this View */
$this->title = '编辑商品';
$id = intval($model->id);
$js = <<<JS
    bindGoods({$id});
JS;
$this->registerJs($js);
?>

<h1><?=$this->title?></h1>
<?=Form::open($model, './@admin/goods/save')?>
    <div class="zd-tab">
        <div class="zd-tab-head">
            <div class="zd-tab-item active">
                基本
            </div>
            <div class="zd-tab-item">
                图片
            </div>
            <div class="zd-tab-item">
                详情
            </div>
            <div class="zd-tab-item">
                其他
            </div>
            <div class="zd-tab-item">
                属性
            </div>
        </div>
        <div class="zd-tab-body">
            <div class="zd-tab-item active">
                <?=Form::text('name', true)?>
                <?=Form::text('series_number')->after('<a data-action="sn" href="javascript:;">生成</a>')?>
                <div class="input-group">
                    <label>分类</label>
                    <select name="cat_id" required>
                        <?php foreach($cat_list as $item):?>
                        <option value="<?=$item['id']?>" <?=$model->cat_id == $item['id'] ? 'selected': '' ?>>
                            <?php if($item['level'] > 0):?>
                                ￂ<?=str_repeat('ｰ', $item['level'] - 1)?>
                            <?php endif;?>
                            <?=$item['name']?>
                        </option>
                        <?php endforeach;?>
                    </select>
                </div>
                <?=Form::select('brand_id', [$brand_list], true)?>
                <?=Form::text('price', true)?>
                <?=Form::text('market_price')?>
            </div>
            <div class="zd-tab-item">
                <?=Form::file('thumb')->dialog(true)?>
                <?=Form::file('picture')->dialog(true)?>
                <div class="multi-image-box">
                    <?php foreach($gallery_list as $item):?>
                    <div class="image-item">
                        <img src="<?=$item->image?>" alt="">
                        <input type="hidden" name="gallery[]" value="<?=$item->image?>">
                        <i class="fa fa-times"></i>
                    </div>
                    <?php endforeach;?>
                    <div class="add-item">
                        <i class="fa fa-plus"></i>
                    </div>
                </div>
            </div>
            <div class="zd-tab-item">
                <script id="container" style="height: 400px" name="content" type="text/plain" required>
                    <?=$model->content?>
                </script>
            </div>
            <div class="zd-tab-item">
                <?=Form::text('weight')?>
                <?=Form::text('stock')?>
                <?=Form::text('keywords')?>
                <?=Form::textarea('description')?>
                <?=Form::checkbox('is_best')?>
                <?=Form::checkbox('is_hot')?>
                <?=Form::checkbox('is_new')?>
                <?=Form::radio('status', [10 => '上架', 0 => '下架'])?>
                <?=Form::radio('type', ['普通商品', '卡密商品', '充值商品'])?>
            </div>
            <div class="zd-tab-item">
                <?=Form::select('attribute_group_id', [$group_list, ['请选择']])?>
                <div class="attribute-box">
                    <div class="attr-box"></div>
                    <div class="product-box">
                        <hr/>
                        <div class="form-horizontal batch-box">
                            <label class="am-form-label">批量设置</label>
                            <div class="input-group">
                                <input type="text" data-type="series_number" placeholder="商家编码">
                            </div>
                            <div class="input-group">
                                <input type="number" data-type="price" placeholder="销售价" pattern="^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$">
                            </div>
                            <div class="input-group">
                                <input type="number" data-type="market_price" placeholder="划线价" pattern="^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$">
                            </div>
                            <div class="input-group">
                                <input type="number" data-type="stock" placeholder="库存数量" pattern="^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$">
                            </div>
                            <div class="input-group">
                                <input type="number" data-type="weight" placeholder="重量" pattern="^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$">
                            </div>
                            <button type="button" class="btn">确定</button>
                        </div>
                        <table class="product-table">

                        </table>
                    </div>
                    <input type="hidden" name="product" value="">
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-success btn-save">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
<?= Form::close('id') ?>


<script id="tpl_spec_table" type="text/template">
    <tbody>
    <tr>
        {{ each attr_list }}
        <th>{{ $value.name }}</th>
        {{ /each }}
        <th>商家编码</th>
        <th>销售价</th>
        <th>划线价</th>
        <th>库存</th>
        <th>重量(kg)</th>
    </tr>
    {{ each product_list item }}
    <tr data-index="{{ $index }}" data-sku-id="{{ item.form.id }}">
        {{ each item.rows td itemKey }}
        <td class="td-spec-value am-text-middle" rowspan="{{ td.rowspan }}">
            {{ td.spec_value }}
        </td>
        {{ /each }}
        <td>
            <input type="text" name="series_number" value="{{ item.form.series_number }}">
        </td>
        <td>
            <input type="number" name="price" value="{{ item.form.price }}"
                   required>
        </td>
        <td>
            <input type="number" name="market_price" value="{{ item.form.market_price }}">
        </td>
        <td>
            <input type="number" name="stock" value="{{ item.form.stock }}" 
                   required>
        </td>
        <td>
            <input type="number" name="weight" value="{{ item.form.weight }}"
                   required>
        </td>
    </tr>
    {{ /each }}
    </tbody>
</script>

<?php $this->extend('./imgDialog');?>
