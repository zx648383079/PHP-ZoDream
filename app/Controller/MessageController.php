<?php
	namespace App\Controller;
	
	use App\Controller\Controller;
	
	use App\Model\MessageModel;
	
	class MessageController extends Controller{
		
		function index(){
			//Auth::user()?"":redirect("/?c=auth");
			$this->send('title','消息');
			
			$message = new MessageModel();
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
			$message= new MessageModel();
			$row= $message->add($data);
			if(!empty($row))
			{
				redirect(url('message'));
			}
		}
		
		function delMsg()
		{
			$id=$_GET['id'];
			$message=new MessageModel();
			$row= $message->delete('id = '.$id);
			redirect(url('message'));
		}
	} 