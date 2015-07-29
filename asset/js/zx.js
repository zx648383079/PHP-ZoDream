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