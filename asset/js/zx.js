(function(){
	var canvas = document.getElementById("cas");
	var ctx = canvas.getContext("2d");
	
	ctx.beginPath();
	ctx.moveTo(20,10);
	ctx.lineTo(300,10);
	ctx.strokeStyle="red";
	ctx.stroke();
	
	ctx.beginPath();
	ctx.moveTo(20,30);
	ctx.lineTo(300,30);
	ctx.strokeStyle="black";
	ctx.stroke();
	
	var x=0;
	var y=0;
	var index=0;
	
	setInterval(function() {
		draw(ctx,x,y);
		index++;
		x=(index%10)*50;
		y=Math.floor(index/10)*50;
		if(index>=90)
		{
			x=0;
			y=0;
			index=0;			
		}
	}, 100); 
	
}());

function draw(ctx,x,y) {
	//var a=Math.round(Math.random()*255);
	//var b=Math.round(Math.random()*255);
	//var c=Math.round(Math.random()*255);
	//var d=Math.random();
	ctx.fillStyle="rgba(255,255,255,0.1)"
	ctx.fillRect(0,0,700,500);
	ctx.fillStyle ="rgb(200,200,0)"; // "rgba("+a+","+b+","+c+","+d+")";
	ctx.fillRect (x, y, 50, 50);
}

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
	code.setAttribute("src","/?c=verify&"+Math.random());
}

