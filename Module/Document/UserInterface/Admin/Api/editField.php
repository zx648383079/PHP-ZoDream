
<form data-type="ajax" action="<?=$this->url('./admin/api/save')?>" method="post" class="form-table" role="form">
    <div class="input-group">
        <label>接口名称</label>
        <input name="name" type="text" class="form-control" placeholder="项目名称" value="<?=$model->name?>">
    </div>
    <div class="input-group">
        <label>请求类型</label>
        <select name="method">
        <?php foreach(['GET', 'POST', 'PUT', 'DELETE', 'OPTION'] as $item):?>
            <option value="<?=$item?>" <?= $item == $model->method ? 'selected' : '' ?>><?=$item?></option>
        <?php endforeach;?>
        </select>
    </div>
    <div class="input-group">
        <label>接口路径</label>
        <input name="uri" type="text" class="form-control" placeholder="项目名称" value="<?=$model->uri?>">
    </div>
    <div class="input-group">
        <label>接口描述</label>
        <textarea name="description" class="form-control" placeholder="备注信息"><?=$model->description?></textarea>
    </div>
    
    <button type="submit" class="btn btn-success">确认保存</button>
    <a class="btn btn-danger" href="javascript:history.go(-1);">取消修改</a>
    <input type="hidden" name="id" value="<?=$model->id?>">
</form>