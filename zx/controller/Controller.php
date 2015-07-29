<?php
	/****************************************************
	*控制器基类
	*
	*
	*作者：zx
	*更新时间：2015/7/23
	*******************************************************/
	
	
	class Controller{

		
		//加载视图
		function show($name="index")
		{
			require(ZXV.$name.".php");
		} 
		
		function ajaxJson($data,$type='JSON')
		{
			switch (strtoupper($type)){
	            case 'JSON' :
	                // 返回JSON数据格式到客户端 包含状态信息
	                header('Content-Type:application/json; charset=utf-8');
	                exit(json_encode($data));
	            case 'XML'  :
	                // 返回xml格式数据
	                header('Content-Type:text/xml; charset=utf-8');
	                exit(xml_encode($data));
	            case 'JSONP':
	                // 返回JSON数据格式到客户端 包含状态信息
	                header('Content-Type:application/json; charset=utf-8');
	                $handler  =   isset($_GET['callback']) ? $_GET['callback'] : 'jsonpReturn';
	                exit($handler.'('.json_encode($data).');');  
	            case 'EVAL' :
	                // 返回可执行的js脚本
	                header('Content-Type:text/html; charset=utf-8');
	                exit($data);            
	        }
		}
	}