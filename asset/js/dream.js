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
		Z('.shade,.window').hide();
	});
	Z(".add").addEvent('click', function() {
		console.log(Z('form').getForm());
		Z().clearForm();
	});
	Z(".create").addEvent('click', function() {
		Z('.shade,.window').show();
		Z("#datetime").val( Helper.date.getNowFormatDate() );
	});
	Helper.ajax.get(APP_URL + "?v=finance", function(msg){
		console.log(Helper.parseJSON(msg));
	});
})();