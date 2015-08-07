<?php
	class MessageController extends Controller{
		function index(){
			//Auth::user()?"":redirect("/?c=auth");
			$this->send('title','登录');
			
			$message=pdo('message');
			$data= $message->select();
			$this->send($data);
			$this->show('message');
		}
		
		function addMsg()
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
		
		function delMsg()
		{
			$id=$_GET['id'];
			$message=pdo('message');
			$row= $message->delete('id = '.$id);
			redirect(url('message'));
		}
	} 