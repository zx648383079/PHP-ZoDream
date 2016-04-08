<?php
defined('APP_DIR') or exit();
$this->extend(array(
    'layout' => array(
        'head'
    ))
);
?>

<div class="main">
    <div class="search">
        <form>
            <select>
                <option value="">百度</option>
                <option value="">必应</option>
            </select>
            <input type="text" name="" value="" placeholder="搜索">
            <button type="submit">搜索</button>
        </form>
    </div>
    
    <div class="table">
        <div class="row">
            <div>
                分类
            </div>
            <div class="column">
                <div>
                    <a href="http://www.baidu.com" target="_blank">百度</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                增加
            </div>
            <div class="column">
                <div>
                    <a id="addCategory" href="javascript:0;">分类</a>
                </div>
                <div>
                    <a id="addWeb" href="javascript:0;">网址</a>
                </div>
            </div>
        </div>
        <div id="category" class="dialog">
            <div>
                <form>
                    <input type="text" name="category" value="" placeholder="分类">
                    <button type="submit">增加</button>
                </form>
            </div>
        </div>
        <div id="web" class="dialog">
            <div>
                <form>
                    名称：<input type="text" name="name" value="" placeholder="名称"> </br>
                    网址：<input type="text" name="url" value="" placeholder="网址"> </br>
                    分类：<select>
                        <option value="">未分类</option>
                    </select>
                    <button type="submit">增加</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        function() {?>
            <script>
                require(['empire/home']);
            </script>
       <?php }
    )
);
?>