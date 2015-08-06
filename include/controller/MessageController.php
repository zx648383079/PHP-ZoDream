<?php
	class MessageController extends Controller{
		function index(){
			//Auth::user()?"":redirect("/?c=auth");
			
			$this->show('message');
		}
		
		function postMessage()
		{
			$data['user_id']=$_POST["id"];
			$data['type']=$_POST["type"];
			$data['content']=$_POST["content"];
			date_default_timezone_set('Etc/GMT-8');     //这里设置了时区
			$data['created']=date("Y-m-d H:i:s");
			$message= pdo("message");
			$row= $message->add($data);
			if(!empty($row))
			{
				redirect(url('message'));
			}
			
		}
	} 