<?php
defined('APP_DIR') or exit();
$this->extend(array(
        'layout' => array(
            'head'
        ))
);
$page = $this->get('page');
?>
<div>
    [栏目设置] [增加采集] [管理采集] [刷新所有信息JS]
</div>

<div>
    <form>
        <button>增加信息</button>
        <select name="dore">
            <option value="1">刷新当前栏目</option>
            <option value="2">刷新首页</option>
            <option value="3">刷新父栏目</option>
            <option value="4">刷新当前栏目与父栏目</option>
            <option value="5">刷新父栏目与首页</option>
            <option value="6" selected>刷新当前栏目、父栏目与首页</option>
        </select>
        <button>提交</button>
        
        搜索: 
          <select name="showspecial">
            <option value="0">不限属性</option>
			<option value="1">置顶</option>
            <option value="2">推荐</option>
            <option value="3">头条</option>
			<option value="7">投稿</option>
            <option value="5">签发</option>
			<option value="8">我的信息</option>
          </select>
          <input name="keyboard" type="text">
          <select name="show">
            <option value="0" selected>不限字段</option>
          </select>
          <select name="infolday">
            <option value="1">全部时间</option>
            <option value="86400">1 天</option>
            <option value="172800">2 
            天</option>
            <option value="604800">一周</option>
            <option value="2592000">1 
            个月</option>
            <option value="7948800">3 
            个月</option>
            <option value="15897600">6 
            个月</option>
            <option value="31536000">1 
            年</option>
          </select>
          <button type="submit">搜索</button>
    </form>
</div>

<div>
    已发布 (9)
    待审核 (0)
    归档
</div>

<div>
我的信息 | 签发信息 | 投稿信息 | 查询重复标题A | 查询重复标题B | 审核栏目全部信息	

管理附件 | 更新数据 | 预览首页 | 预览栏目
</div>

<table>
    <thead>
    <tr>
        <th></th>
        <th>ID</th> 
        <th>标题</th> 
        <th>发布者</th> 
        <th>发布时间</th> 
        <th>点击</th> 
        <th>评论</th> 
        <th>操作</th> 
    </tr>
    </thead>
    <tbody>
        <?php foreach ($page->getPage() as $value) {?>
            <tr>
                <td><input type="checkbox" name="" value="<?php echo $value['id'];?>"></td>
                <td><?php echo $value['id'];?></td>
                <td></td>
                <td><?php echo $value['username'];?></td>
                <td>
                    <input name="newstime[]" type="text" value="<?php echo $value['newstime'];?>">
                </td>
                <td><?php echo $value['onclick'];?></td>
                <td><?php echo $value['plnum'];?></td>
                <td>修改 | 简改 | 删除</td>
            </tr>
        <?php }?>
    </tbody>
    <tfoot>
        <tr>
            <th><input type="checkbox"></th>
            <th colspan="7">
                 <button type="submit">删除</button> 
                <button type="submit">取消审核</button> 
                <button type="submit">刷新</button> 
                <button type="submit">推送</button> 
                <select name="isgood">
                    <option value="0">不推荐</option>
                </select>
                <button type="submit">推荐</button> 
                <select name="firsttitle">
                    <option value="0">取消头条</option>
                </select>
                 <button type="submit">头条</button> 
                <button type="submit">归档</button> 
                <select name="istop">
                    <option value="0">不置顶</option>
                    <option value="1">一级置顶</option>
                    <option value="2">二级置顶</option>
                    <option value="3">三级置顶</option>
                    <option value="4">四级置顶</option>
                    <option value="5">五级置顶</option>
                    <option value="6">六级置顶</option>
                    <option value="7">七级置顶</option>
                    <option value="8">八级置顶</option>
                    <option value="9">九级置顶</option>
                </select>
                 <button type="submit">置顶</button> 
                <button type="submit">修改时间</button> 
                <button type="submit">推送至专题</button> 
            </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="7">
                <?php $page->pageLink();?>
                
                
                 <button type="submit">移动</button> 
                <button type="submit">复制</button> 
           </th>
        </tr>
        <tr>
            <th></th>
            <th colspan="7">备注：多选框蓝色为未审核信息；发布者红色为会员投稿；信息ID粗体为未生成,点击ID可刷新页面.</th>
        </tr>
    </tfoot>
</table>
<?php
$this->extend(array(
        'layout' => array(
            'foot'
        ))
);
?>