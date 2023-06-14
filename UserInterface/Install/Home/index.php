<?php
defined('APP_DIR') or exit();
use Zodream\Template\View;
/** @var $this View */
$this->title = '开始之前';
?>
<div class="page-header">
	开始之前
</div>
<div class="page-body">
	<p>欢迎使用 ZoDream 。在开始前，我们需要您数据库的一些信息。请准备好如下信息。</p>
	<ol>
		<li>数据库名</li>
		<li>数据库用户名</li>
		<li>数据库密码</li>
		<li>数据库主机</li>
		<li>数据表前缀（table prefix，特别是当您要在一个数据库中安装多个 ZoDream 时）</li>
	</ol>
	<p>我们会使用这些信息来创建一个
		<code>database.php</code>文件。
	<strong>如果自动创建未能成功，不用担心，您要做的只是将数据库信息填入配置文件。您也可以在文本编辑器中打开<code>database.php</code>，填入您的信息，并将其另存为<code>database.php</code>。</strong>
		需要更多帮助？<a href="https://zodream.cn">看这里</a>。</p>
	<p>绝大多数时候，您的网站服务提供商会给您这些信息。如果您没有这些信息，在继续之前您将需要联系他们。如果您准备好了…</p>
</div>
<div class="page-footer">
	<a class="btn btn-primary" href="<?=$this->url('./environment');?>">开始安装</a>
</div>