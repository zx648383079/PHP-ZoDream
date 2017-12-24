<?php
defined('APP_DIR') or exit();
use Zodream\Infrastructure\Support\Html;
use Zodream\Template\View;
/** @var $this View */
$js = <<<JS
$("#page-list").page({
    url: '/admin.php/blog/list',
    deleteUrl: '/admin.php/blog/delete',
    updateUrl: '',
    createRow: function(data) {
        return '<tr data-id="'+data.id+'"><td><i class="checkbox"></i></td><td data-name="title">'+data.title+'</td><td>'+data.term+'</td><td>'+data.created_at+'</td><td><a class="btn" href="/admin.php/blog/detail?id='+data.id+'">编辑</a><a class="btn delete">删除</a></td></tr>';
    }
});
JS;

$this->extend('layout/header')
    ->registerJsFile('@jquery.pager.min.js')
    ->registerJsFile('@jquery.page.min.js')
    ->registerJs($js, View::JQUERY_READY);
?>
<div class="page-header">
    <ul class="path">
        <li>
            <a>首页</a>
        </li>
        <li class="active">
            列表
        </li>
    </ul>
    <div class="title">
        列表
    </div>
    <div class="actions">
        <a class="btn" href="/admin.php/blog/detail">添加</a>
    </div>
</div>

<div id="page-list">
    <div class="page-search">
        <form class="form-horizontal">
            <div class="input-group">
                <label>姓名</label>
                <input type="text">
            </div>
            <div class="input-group">
                <label>等级</label>
                <select>
                    <option>选择</option>
                </select>
            </div>
            <button class="btn">搜索</button>
        </form>
    </div>


    <table>
        <thead>
            <tr class="sort-row">
                <th>
                    <i class="checkbox checkAll"></i>
                </th>
                <th >
                    标题
                </th>
                <th data-name="term_id" class="sort-desc">
                    分类
                </th>
                <th class="sort-desc" name="create_at">
                    发布时间
                </th>
                <th>
                    操作
                </th>
            </tr>
            <tr class="filter-row">
                <th>
                    
                </th>
                <th>
                    <input name="title" type="text">
                </th>
                <th>
                    <input type="text" name="term">
                </th>
                <th>
                    
                </th>
                <th>
                    
                </th>
            </tr>
        </thead>
        <tbody class="page-body">
            
        </tbody>
        <tfoot>
                <tr>
                <td>
                    <i class="checkbox checkAll"></i>
                </td>
                <td colspan="4" class="left">
                    <a class="btn deleteAll">删除</a>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<?php $this->extend('layout/footer'); ?>