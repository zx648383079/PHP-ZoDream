<ul>
    <li><a href="<?=$this->url('./admin')?>"><i class="fa fa-home"></i><span>首页</span></a></li>
    <li class="expand"><a href="javascript:;"><i class="fa fa-briefcase"></i><span>小说管理</span></a>
        <ul>
            <li><a href="<?=$this->url('./admin/book')?>"><i class="fa fa-list"></i><span>小说列表</span></a></li>
            <li><a href="<?=$this->url('./admin/book/create')?>"><i class="fa fa-list"></i><span>新建小说</span></a></li>
        </ul>
    </li>
    <li class="expand">
        <a href="javascript:;"><i class="fa fa-briefcase"></i><span>分类管理</span></a>
        <ul>
            <li><a href="<?=$this->url('./admin/category')?>"><i class="fa fa-list"></i><span>分类列表</span></a></li>
            <li><a href="<?=$this->url('./admin/category/create')?>"><i class="fa fa-list"></i><span>新建分类</span></a></li>
        </ul>
    </li>
    <li class="expand">
        <a href="javascript:;"><i class="fa fa-briefcase"></i><span>作者管理</span></a>
        <ul>
        <li><a href="<?=$this->url('./admin/author')?>"><i class="fa fa-list"></i><span>作者列表</span></a></li>
            <li><a href="<?=$this->url('./admin/author/create')?>"><i class="fa fa-list"></i><span>新建作者</span></a></li>
        </ul>
    </li>
</ul>