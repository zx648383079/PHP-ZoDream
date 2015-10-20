(function() {
	var editor = CKEDITOR.replace('content');
	
	
	
	Z("#addPage").addEvent("click", function() {
		Z("#page").toggle();
	});
	
	Z("#page .save").addEvent("click", function() {
		var data = Z('#page form').getForm();
		if(data == undefined) {
			return;
		}
		data['content'] = editor.getData();
		zodream.ajax.post(zodream.url(), data, function(msg){
			if(msg.status == 0)
			{
				var id =Z("@id").val(),
					page = Z("@page").val();
				if( id > 0) {
					var index = false;
					 Z("#pagelist td").forE(function( e , i ) {
						if(index) {
							Z(e).html(page);
							index = false;
						}
						if(Z(e).html() == id) {
							index = true;
						}
					});
				}else if(msg.data > 0){
					var obj = document.createElement("tr");
					Z(obj).html("<td>"+ msg.data + "</td><td>" + page + 
						"</td><td><input type=\"checkbox\"></td><td><a href=\"javascript:;\">编辑</a> <a href=\"javascript:;\">删除</a></td>" );
					Z("#pagelist").addChild(obj);
				}
				
				if(msg.data > 0){
					Z("#page").hide();
					Z().clearForm('#page form');
				}else {
					alert("操作失败！");
				}
			}
		});
	});
	
	Z(".sidebox .close").addEvent('click', function() {
		Z(".sidebox").hide();
	});
	
	Z("#pagelist input").addEvent("change", function() {
		var id = Z(Z(Z(this).parents(2)).children()).html();
		zodream.ajax.get(zodream.url() + "&mode=show&id=" + id);
	});
	
	Z("#pagelist a").addEvent("click", function() {
		var tr = Z(this).parents(2),
			id = Z(Z(tr).children()).html();
		switch (Z(this).html()) {
			case "编辑":
				Z("@id").val(id);
				zodream.ajax.get(zodream.url() + "&mode=edit&id=" + id, function(msg) {
					if(msg.status == 0) {
						Z("@page").val(msg.data.page);
						editor.setData(msg.data.content);
						Z("#page").show();
					}
				});
				break;
			case "删除":
				zodream.ajax.get(zodream.url() + "&mode=delete&id=" + id, function(msg) {
					if(msg.status == 0) {
						Z(tr).removeSelf();
					}
				});
				break;
			default:
				break;
		}
	});
})();