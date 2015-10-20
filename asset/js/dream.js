(function() {
	/**
	 * 导航栏
	 */
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
	/**
	 * 自定义全局方法
	 */
	zodream.extend({
		selectElement: null,
		select: function( element ) {
			if(this.selectElement) {
				Z(this.selectElement).css("background-color", "transparent");				
			}
			if(this.selectElement === element) {
				this.selectElement = null;
				return;
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
		},
		
	});
	
	/**
	 * 展开分类
	 */
	Z(".treebox li .more").addEvent('click', zodream.more);
	/**
	 * 选中分类
	 */
	Z(".treebox li").addEvent('click', zodream.selected );
})();