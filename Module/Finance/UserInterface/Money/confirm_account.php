<div class="container theme-showcase">
    <div class="row">
        <div class="col-md-9">
            <form data-type="ajax" action="<?=$this->url('./money/save_budget')?>" method="post" class="form-table" role="form">
                <div class="input-group">
                    <label for="asset_name">配置项目</label>
                    <input type="text" class="form-control" id="asset_name" value="<?php echo $asset_name;?>" readonly style="width:50%" />
                </div>
                <div class="input-group">
                    <label for="number">资金</label>
                    <input type="text" class="form-control" id="number" value="<?php echo $number;?>" readonly style="width:50%" />
                </div>
                <div class="input-group">
                    <label for="earnings_number">实现收益</label>
                    <input type="text" class="form-control" name="earnings_number" id="earnings_number" placeholder="实现收益" onkeyup="value=value.replace(/[^\d.]/g,'')" style="width:50%" />
                </div>
                <input type="hidden" name="id" value="<?php echo $id;?>">
                <button type="submit" class="btn btn-default">保存</button>
            </form>
        </div>
    </div>
</div>