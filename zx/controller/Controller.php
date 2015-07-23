<?php
	class Controller{

		
		//加载视图
		function show($name="index")
		{
			require(ZXV.$name.".php");
		} 
	}