(function() {
	zodream.extend({
		soStatus: false
	});
	
	Z("#sotext input").addEvent("input", function() {
		if(!zodream.soStatus) {
			Z("#viewtree li").forE(function(e) {
				Z(Z(e).getChildren("ul,span")).removeSelf();
				Z("#viewtree ul").addChild(e);
			});
		}
		var text = Z(this).val();
		if(text == '') {
			Z("#viewtree li").show();
		}else {
			var texts = text.split(" ");
			Z("#viewtree li").forE(function(e) {
				Z(e).show();
				if(Z(e).html().indexOf(text) < 0) {
					Z(e).hide();
				}
				for (var i = 0,len = texts.length; i < len; i++) {
					if( texts[i] !="" && Z(e).html().indexOf(texts[i]) >= 0) {
						Z("#viewtree ul").addChild(e);
						Z(e).show();
					}
				}
			});
		}
	});
	
	Z("#viewtree li").addEvent('click', function() {
		var child = Z(this).getChildren( "ul");
			if( child.length < 1 || Z(child).css("display") == "none" ) {
				zodream.select(this);
				zodream.ajax.get(zodream.url() + "&id=" + Z(zodream.selectElement).attr("data"), function(msg) {
					if(msg.status == 0) {
						var title = document.createElement("div");
						Z(title).html(msg.data.title).addClass("title");
						var div = document.createElement("div");
						div.innerHTML = msg.data.content;
						Z("#document").removeChild();
						Z("#document").addChild(title, div);
					}			
				});
			};
		
	});
})();