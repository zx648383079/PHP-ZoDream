<?php
defined('APP_DIR') or exit();
$this->extend(array(
	'layout' => array(
		'head'
	))
);
?>
<div class="zd-container">
<div class="ms-Grid off">
	<div class="ms-Grid-row">
		<div class="ms-Grid-col ms-u-md8">
			<h3 class="headTitle">关于</h3>
			<?php $this->ech('data.0.value');?>
		</div>
		<div class="ms-Grid-col ms-u-md4">
			<div class="ms-PersonaCard">
                <div class="ms-PersonaCard-persona">
                    <div class="ms-Persona ms-Persona--xl">
                    <div class="ms-Persona-imageArea">
                        <div class="ms-Persona-initials ms-Persona-initials--blue">ZD</div>
                        <img class="ms-Persona-image" src="../persona/Persona.Person2.png">
                    </div>
                    <div class="ms-Persona-presence"></div>
                    <div class="ms-Persona-details">
                        <div class="ms-Persona-primaryText" title="Alton Lafferty">邹雄</div>
                        <div class="ms-Persona-secondaryText">PHP开发</div>
                        <div class="ms-Persona-tertiaryText">爱好: 阅读</div>
                        <div class="ms-Persona-optionalText">千里之行始于足下</div>
                    </div>
                    </div>
                </div>
                <ul class="ms-PersonaCard-actions">
                    <li id="chat" class="ms-PersonaCard-action is-active"><i class="ms-Icon ms-Icon--chat"></i></li>
                    <li id="phone" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--phone"></i></li>
                    <li id="video" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--video"></i></li>
                    <li id="mail" class="ms-PersonaCard-action"><i class="ms-Icon ms-Icon--mail"></i></li>
                    <li class="ms-PersonaCard-overflow" alt="View profile in Delve" title="View profile in Delve">个人资料</li>
                </ul>
                <div class="ms-PersonaCard-actionDetailBox">
                    <ul id="detailList" class="ms-PersonaCard-detailChat">
                    <li id="chat" class="ms-PersonaCard-actionDetails detail-1">
                        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">QQ:</span> <a class="ms-Link" href="#">648383079</a></div>
                    </li>
                    <li id="phone" class="ms-PersonaCard-actionDetails detail-2">
                        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">私人:</span> 18616391245</div>
                    </li>
                    <li id="video" class="ms-PersonaCard-actionDetails detail-3">
                        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">Skype:</span> <a class="ms-Link" href="#"></a></div>
                    </li>
                    <li id="mail" class="ms-PersonaCard-actionDetails detail-4">
                        <div class="ms-PersonaCard-detailLine"><span class="ms-PersonaCard-detailLabel">私人:</span> <a class="ms-Link" href="mailto:648383079@qq.com">648383079@qq.com</a></div>
                    </li>
                    </ul>
                </div>
                </div>
		</div>
	</div>
	<div class="ms-Grid-row">
   		<div class="ms-Grid-col ms-u-md4">
             <h3 class="headTitle">提交建议</h3>
            <form method="post" action="<?php $this->url();?>">
                <div class="ms-TextField">
                    <input type="text" name="name" class="ms-TextField-field" placeholder="姓名" value="<?php $this->ech('name');?>">
                </div>
                <div class="ms-TextField">
                    <input type="email" name="email" class="ms-TextField-field" placeholder="邮箱" value="<?php $this->ech('email');?>" required>
                </div>
                <div class="ms-TextField">
                    <input type="text" name="title" class="ms-TextField-field" placeholder="标题">
                </div>
                <div class="ms-TextField ms-TextField--multiline">
                    <textarea name="content" class="ms-TextField-field" placeholder="内容" required></textarea>
                </div>
                <button class="ms-Button">发送</button>
            </form>
		</div>
   		<div class="ms-Grid-col ms-u-md8">
			<iframe class="map" src="/map.html" frameborder="0"></iframe>
   		</div>
   	</div>
</div>	
</div>
<?php
$this->extend(array(
	'layout' => array(
		'foot'
	)), array(
        'Jquery.PersonaCard',
        function() {?>
<script type="text/javascript">
$('.ms-PersonaCard').PersonaCard();
</script>
     <?php }
    )
);
?>