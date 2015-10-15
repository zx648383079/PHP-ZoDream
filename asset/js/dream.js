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
	});
	Z(".add").addEvent('click', function() {
		Z('.shade,#create').hide();
		var obj = document.createElement("li");
		obj.innerHTML = Z("@title").val();
		Z(obj).addEvent('click', zodream.selected );
		if( zodream.selectElement === null) {
			Z(".editbox ul").addChild(obj);
			return;
		}
		
		var uls = Z(zodream.selectElement).getChildren("ul");
		if(uls.length > 0) {
			Z(uls[0]).addChild(obj);
			return;
		}
		var span = document.createElement("span");
		span.innerHTML = "+";
		span.className = "more";
		Z(span).addEvent('click', zodream.more);
		var ul = document.createElement("ul");
		ul.appendChild(obj);
		
		Z(zodream.selectElement).addChild(span);
		Z(zodream.selectElement).addChild(ul);
		
		
	});
	Z(".create").addEvent('click', function() {
		Z('.shade,#create').show();
	});
	zodream.extend({
		selectElement: null,
		select: function( element ) {
			if(this.selectElement) {
				Z(this.selectElement).css("background-color", "transparent");				
			}
			this.selectElement = element;
			
			if(this.selectElement) {
				Z(this.selectElement).css("background-color", "#e82");		
			}
		},
		selected: function() {
			var child = Z(this).getChildren( "ul");
			if( child.length < 1 || Z(child).css("display") == "none" ) {
				zodream.select(this);
			};
		},
		more: function() {
			Z(Z(this).next()).toggle();
			if(Z(this).html() == "+") {
				Z(this).html("-");			
			}else {
				zodream.select(Z(this).parents());
				Z(this).html("+");
			}
		}
	});
	
	Z(".editbox li .more").addEvent('click', zodream.more);
	Z(".editbox li").addEvent('click', zodream.selected );
	Z(".editbox .tool a").addEvent('click', function() {
		Z(".editbox li", true ).forE(function(e , i , ele) {
			if(Z(e).css("background-color") !== "transparent") {
				switch (Z(ele).html()) {
					case "删除":
						if(Z(e).getSibling().length < 1) {
							Z(Z(Z(e).parents()).prev()).removeSelf();
							Z(Z(e).parents()).removeSelf();
						}else {
							Z(e).removeSelf();							
						}
						break;
					case "上移":
						var pre = Z(e).prev();
						if(pre) {
							Z(pre).insertBefore(e);
						}
						break;
					case "下移":
						var next = Z(e).next();
						if(next) {
							Z(next).insertAfter(e);
						}
						break;
					default:
						break;
				};
			}
		}, this );
	});
})();