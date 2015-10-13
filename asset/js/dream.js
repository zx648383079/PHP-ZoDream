(function() {
	Z(".nav .brand").addEvent('click',function() {
		if(!Z(".nav ul").attr("class"))
		{
			Z().attr("class","open");
			Z(".short").addClass("open");
		}else{
			Z().attr("class","");
			Z(".short").removeClass("open");
		}
	});
	Z(".close").addEvent('click', function() {
		Z('.shade,#create').hide();
		Z('.shade,#view').hide();
	});
	Z(".add").addEvent('click', function() {
		Helper.ajax.post(APP_URL + "?v=create", Z('form').getForm(), function(msg){
			if(msg.status == 0)
			{
				Z().clearForm();
				Helper.refresh();
			}
		});
		
	});
	Z("#view .delele").addEvent('click', function() {
		if( confirm("是否确认删除此项？") ) {
			Helper.ajax.get( APP_URL + "?v=delete&id=" + Z('#view .body .id').html(), function(msg){
				if(msg.status == 0)
				{
					Helper.refresh();
				}
			});
		}
	});
	Z(".create").addEvent('click', function() {
		Z('.shade,#create').show();
		Z("#create .head").html("新增纪录");
		Z(".add").html("新增");
		Z("#create @id").val("0");
		Z("#create @happen").val( Helper.date.getNowFormatDate() );
	});
	Z("#view .edit").addEvent('click', function() {
		Z('.shade,#view').hide();
		Z('.shade,#create').show();
		Z("#create .head").html("修改纪录");
		Z(".add").html("修改");
		Z("#create @id").val(Z('#view .body .id').html());
		Z("#create @money").val(Z('#view .body .money').html());
		Z("#create @happen").val(Z('#view .body .time').html());
		Z("#create @mark").val(Helper.htmlTo(Z('#view .body .mark').html()));
		var kind = Z('#view .body .kind').html() == "支出"?"0":"1";
		Z("#create @kind").forE(function(e) {
			if(e.value == kind) {
				e.checked = true;
			}
		});
	});
	Z("#listbox tbody tr").addEvent("dblclick", function() {
		Z('.shade,#view').show();
		var id = this.children[0].innerHTML;
		Helper.ajax.get(APP_URL + "?v=finance&id=" + id, function(msg) {
			var data = msg.data[0];
			Z('#view .body .id').html(data.id);
			Z('#view .body .kind').html(data.kind == 1?"收入":"支出");
			Z('#view .body .money').html(data.money);
			Z('#view .body .time').html(data.happen);
			Z('#view .body .mark').html(data.mark);
		});
	});
})();