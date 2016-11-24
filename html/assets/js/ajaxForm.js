$(document).ready(function () {
	var validate = $(".ajax-form").Validform({
		showAllError:true,
		label: ".ms-Label",
		tiptype: function(msg, o, cssctl){
			//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
			if(!o.obj.is("form")){
				var objtip=o.obj.siblings(".Validform_checktip");
				cssctl(objtip, o.type);
				objtip.text(msg);
			}else{
				var objtip = $("#tips");
				switch(o.type){
					case 1:
						objtip.attr("class", "alert-info").html(msg).show();//checking;
						break;
					case 2:
						objtip.attr("class", "alert-success").html(msg).hide();//passed
						break;
					case 4:
						objtip.attr("class", "ware").html(msg).show();;//for ignore;
						break;
					default:
						objtip.attr("class", "alert-error").html(msg).show();;//wrong
				}
			}
		},
		ajaxPost:true,
		callback:function(data){
			var tip = validate.forms.prev(".tips");
			if (data.status == 1) {
				tip.attr("class", "alert-success").html(data.info + ' 页面即将跳转~').show();
				setTimeout(function(){
					if (BACKURL) {
						window.location.href = BACKURL;
						return;
					}
					window.location.href = data.url || "/user.php";
				},1500);
			} else if (data.status == 3) {
				window.location.reload();
			} else if (data.status == 4) {
				/**
				 * replace html , happen in the find.html
				 */
				tip.hide();
				validate.forms.html(data.template);
				return;
			}  else{
				tip.attr("class", "alert-error").html(data.info).show();
				//changeVerify();
				setTimeout(function(){
					if (data.url) {
						location.href = data.url;
					}else{

					}
				},1500);
			}
		}
	});
});