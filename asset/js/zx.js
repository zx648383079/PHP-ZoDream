//把数组转成能提交的数据
function topost(data)
{
	var str="";
	
	if(data instanceof Object)
	{
		for(var key in data)
		{
			if(str=="")
			{
				str=key+"="+data[key];
			}else{
				str+="&"+key+"="+data[key];
			}
		}
	}else{
		str=data;
	}
	
	return str;
}
//刷新验证码
var code= document.getElementById("code");
code.onclick=function()
{
	code.setAttribute("src","/verify-index?"+Math.random());
}