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
		Z('.shade').hide();
		Z('.window').hide();
	});
	Z(".add").addEvent('click', function() {
		console.log(Z('form').getForm());
		Z().clearForm();
	});
	Z(".create").addEvent('click', function() {
		Z('.shade').show();
		Z('.window').show();
		Z("#datetime").val( Helper.date.getNowFormatDate() );
	});
	
})();